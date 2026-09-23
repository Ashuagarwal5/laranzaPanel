<?php namespace App\Services;

/**
 * The fixed set of app screens a push notification can deep-link to on tap.
 *
 * Each key is exactly the client's expo-router segment (`/dashboard`,
 * `/reward-catalog`, ...), so the client side is a plain lookup rather than a
 * translation layer - see client `src/lib/push.js`'s `SCREEN_ROUTES`. Keep the
 * two lists in sync when adding a screen.
 */
class PushScreens
{
    public static function options()
    {
        return [
            'dashboard'      => 'Dashboard',
            'reward-catalog' => 'Reward catalog',
            'cart'           => 'My redemption list',
            'claim-history'  => 'Claim history',
            'reward-history' => 'Reward history',
            'notifications'  => 'Notifications',
            'profile'        => 'Profile',
        ];
    }

    /**
     * True for a known screen key, and for empty/null - a message is allowed
     * to carry no deep link at all, it just opens the app.
     */
    public static function exists($key)
    {
        return $key === null || $key === '' || array_key_exists($key, self::options());
    }
}
