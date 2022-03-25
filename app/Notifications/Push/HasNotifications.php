<?php

namespace App\Notifications\Push;

trait HasNotifications
{
    /**
     * Process Data for Push Request:
     * @param $data
     * @param null $group
     * @return bool
     */
    public static function pushNotification($data = [], $group = null): bool
    {
        if (
            !config('mobile_app.push_notification.notify') ||
            empty($data['subject']) ||
            empty($data['message']) ||
            empty($data['device_id'])
        ) {
            return false;
        }

        $subject = str_replace('[', '', $data['subject']);
        $subject = str_replace(']', '', $subject);
        $message = str_replace('[', '', $data['message']);
        $message = str_replace(']', '', $message);

        $response = null;

        if (empty($group)) {
            $pushData = [
                'id' => $data['device_id'],
                'title' => $subject,
                'message' => $message,
                'data' => 'Order ID: ' . (!empty($data['order']) ? $data['order'] : null)
                    . ', Status: ' . (!empty($data['status']) ? $data['status'] : null),
            ];

            //sending Request:
            $response = self::singleRequest($pushData);
        } else {
            $pushData = [
                'title' => $subject,
                'message' => $message,
            ];

            //sending Request:
            $response = self::groupRequest($pushData, $group);
        }

        $result = json_decode($response, true);

        if (empty($result['id'])) {
            \Log::error('Push Notification Failed:: ');
            \Log::error($response);

            return false;
        }

        return true;
    }

    /**
     * Send Push Request for single user:
     * @param array $data
     * @return string
     */
    public static function singleRequest($data = []): string
    {
        $fields = [
            'app_id' => config('mobile_app.push_notification.app_id'),
            'include_player_ids' => [$data['id']],
            'headings' => [
                'en' => $data['title'],
            ],
            'contents' => [
                'en' => $data['message'],
            ],
            'data' => [
                'data' => $data['data'],
            ],

        ];

        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Send Push Request for Group of User:
     * @param array $data
     * @param $group
     * @return string
     */
    public static function groupRequest($data, $group): string
    {
        $fields = [
            'app_id' => config('mobile_app.push_notification.app_id'),
            'included_segments' => [$group],
            'headings' => [
                'en' => $data['title'],
            ],
            'contents' => [
                'en' => $data['message'],
            ],
        ];

        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . config('mobile_app.push_notification.api_key'),
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
