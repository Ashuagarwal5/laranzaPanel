<?php

namespace App\Helpers;

class PushNotification
{
    public static function send($message, $deviceToken, $data=null)
    {
        $tokens		=	array();
        //=========== send pushes =============//
        if (!empty($deviceToken)) {
            $apiKey   = "AAAAIgRIreE:APA91bG-b3oMs_rT4uHCSxAwBXrZqSXj5GWMbz3rfONi4OQO15_RwP9HrM4FLkzx71BJ2H2gV6DNJ4P7ZPrj4QZMRLZ2nfR_-u9ptLiIjPlYqITA0-RyF14ImGIwHIdEFQCVu3xCu95X"; // server key //
            $url = 'https://fcm.googleapis.com/fcm/send';
            $image=null;
            $user_id=0;
            $fcmMsg = array(
          'body' =>$message->description ,
          'title' => $message->title,
          'sound' => "default",
          'icon'=> '',
          'vibrate' => 1,
          'data'=>$data,
          "click_action" =>null
      );

            $fcmMsg = array_filter($fcmMsg);
            //$fcmMsg = array_merge($fcmMsg,$extradata);

            $fcmFields = array(
          'registration_ids' => $deviceToken,
          'priority' => 'high',
          'notification' => $fcmMsg,
          'data' => $fcmMsg
      );
            $headers = array(
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
      );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            }
            curl_close($ch);
        }
        // =========== ends =================== //
        return $result;
    }


    public static function getAllTokenDevicesNew($memIds)
    {
        //->where('notification_status','On')
        //return DB::table('users')->whereIn('id', $memIds)->pluck('DeviceToken')->toArray();
        //return DB::table('device_tokens')->whereIn('user_id', $memIds)->get();
    }
}
