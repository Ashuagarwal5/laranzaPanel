<?php namespace App\Services;

use App\WebsiteSetting;
use Cache;
use Log;

/**
 * Firebase Cloud Messaging, HTTP v1 API.
 *
 * The legacy `fcm.googleapis.com/fcm/send` endpoint (server key in an
 * Authorization header) was shut down by Google in June 2024. v1 instead
 * needs an OAuth2 access token minted from a Firebase service-account JSON
 * (Firebase Console -> Project Settings -> Service Accounts -> Generate new
 * private key), pasted into Website Settings -> Push Notification Settings.
 *
 * No firebase/kreait package is installed, and none is needed: the JWT this
 * requires is just a signed, base64url-encoded string, which PHP's own
 * openssl extension can build directly.
 */
class FcmV1
{
    const TOKEN_CACHE_KEY = 'fcm_v1_access_token';
    // Tokens are valid for 3600s; cache a little under that so a request never
    // races the real expiry.
    const TOKEN_CACHE_TTL = 3300;

    /**
     * The decoded service-account JSON from Website Settings, or null if it
     * hasn't been configured / doesn't parse.
     */
    protected static function credentials()
    {
        $raw = WebsiteSetting::getGeneralSetting()->fcm_service_account_json ?? null;
        if (empty($raw)) {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded) || empty($decoded['private_key']) || empty($decoded['client_email']) || empty($decoded['project_id'])) {
            Log::error('FcmV1: fcm_service_account_json is not a valid Firebase service-account key.');
            return null;
        }

        return $decoded;
    }

    protected static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * A cached OAuth2 access token for the service account, or null if
     * credentials are missing/invalid or the token exchange fails.
     */
    public static function accessToken()
    {
        $cached = Cache::get(self::TOKEN_CACHE_KEY);
        if ($cached) {
            return $cached;
        }

        $token = self::fetchAccessToken();
        if ($token) {
            Cache::put(self::TOKEN_CACHE_KEY, $token, self::TOKEN_CACHE_TTL);
        }

        return $token;
    }

    /**
     * Performs the actual OAuth2 token exchange. Never cached here - a
     * failure (bad credentials, Google API hiccup) must be retried on the
     * next send, not frozen into the cache for TOKEN_CACHE_TTL.
     */
    protected static function fetchAccessToken()
    {
        $credentials = self::credentials();
        if (!$credentials) {
            return null;
        }

        $now = time();
        $header  = self::base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $claims  = self::base64UrlEncode(json_encode([
            'iss'   => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]));
        $unsigned = $header.'.'.$claims;

        $signature = '';
        $signed = openssl_sign($unsigned, $signature, $credentials['private_key'], 'sha256WithRSAEncryption');
        if (!$signed) {
            Log::error('FcmV1: failed to sign the service-account JWT.');
            return null;
        }

        $jwt = $unsigned.'.'.self::base64UrlEncode($signature);

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]));
        $result = curl_exec($ch);
        $error  = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error('FcmV1: token exchange curl error: '.$error);
            return null;
        }

        $body = json_decode((string) $result, true);
        if (empty($body['access_token'])) {
            Log::error('FcmV1: token exchange failed: '.(string) $result);
            return null;
        }

        return $body['access_token'];
    }

    /**
     * Send one push to one device token. $data values are cast to strings -
     * FCM v1 rejects anything else in the data payload.
     *
     * Never throws: a push failure must not break whatever triggered it.
     */
    public static function send($deviceToken, $title, $body, array $data = array())
    {
        $credentials = self::credentials();
        if (!$credentials || empty($deviceToken)) {
            return ['status' => 'error', 'msg' => 'FCM is not configured or the device has no token.'];
        }

        $token = self::accessToken();
        if (!$token) {
            return ['status' => 'error', 'msg' => 'Could not obtain an FCM access token.'];
        }

        $stringData = array();
        foreach ($data as $key => $value) {
            $stringData[$key] = (string) $value;
        }

        $payload = [
            'message' => [
                'token'        => $deviceToken,
                'notification' => ['title' => (string) $title, 'body' => (string) $body],
                'data'         => $stringData,
                'android'      => ['priority' => 'high'],
            ],
        ];

        $url = 'https://fcm.googleapis.com/v1/projects/'.$credentials['project_id'].'/messages:send';
        $ch  = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$token,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $result = curl_exec($ch);
        $error  = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            Log::error('FcmV1: send curl error: '.$error);
            return ['status' => 'error', 'msg' => $error];
        }
        if ($status < 200 || $status >= 300) {
            Log::error('FcmV1: send failed ('.$status.'): '.$result);
            return ['status' => 'error', 'msg' => $result];
        }

        return ['status' => 'success', 'msg' => $result];
    }
}
