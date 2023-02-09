<?php

namespace App\Repository\API\V1\Notifications;

use App\Interfaces\API\V1\Notifications\NotificationMessagingRepositoryInterface;
use App\Models\Partner;
use App\Models\PartnerNotificationDevice;
use App\Models\User;
use App\Models\UserNotificationDevice;
use App\Utils\Response;

class NotificationMessagingRepository implements NotificationMessagingRepositoryInterface
{
    use Response;

    /**
     * Send notification to user registered devices
     * 
     * @param mixed $message
     * @param User $user
     * @return JSONResponse
     */
    public function sendNotificationToUser($message, User $user)
    {
        try {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $device_tokens = UserNotificationDevice::where('user_id', $user->id)->pluck('device_key');

            $serverKey = env("FIREBASE_FCM_SKEY");

            $data = [
                "registration_ids" => $device_tokens,
                "notification" => [
                    "title" => $message['title'],
                    "body" => $message['body'],
                ]
            ];
            $encodedData = json_encode($data);

            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);

            return $this->responseData($result, __("Notification sent to user devices."));
        } catch (\Throwable $th) {
            return $this->responseError(["msg"=> "There was a problem with fetching the data, try again.", "exception" => $th], 500);
        }
    }

    /**
     * Send notification to partner registered devices
     * 
     * @param mixed $message
     * @param Partner $partner
     * @return JSONResponse
     */
    public function sendNotificationToPartner($message, Partner $partner)
    {
        try {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $device_tokens = PartnerNotificationDevice::where('partner_id', $partner->id)->pluck('device_key');

            $serverKey = env("FIREBASE_FCM_SKEY");

            $data = [
                "registration_ids" => $device_tokens,
                "notification" => [
                    "title" => $message["title"],
                    "body" => $message["body"],
                ]
            ];
            $encodedData = json_encode($data);

            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);

            return $this->responseData($result, __("Notification sent to partner devices."));
        } catch (\Throwable $th) {
            return $this->responseError(["msg"=> "There was a problem with fetching the data, try again.", "exception" => $th], 500);
        }
    }
}
