<?php

namespace Trin4ik\LaravelExpoPush\Channels;

use Illuminate\Notifications\Notification;
use Trin4ik\LaravelExpoPush\ExpoPush;

class ExpoPushChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return bool
     */
    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notifiable, 'routeNotificationForExpoPush')) {
            $expo_token = $notifiable->routeNotificationForExpoPush($notifiable);
        } else {
            $expo_token = $notifiable->getKey();
        }

        $data = method_exists($notification, 'toExpoPush')
            ? $notification->toExpoPush($notifiable)->toArray()
            : $notification->toArray($notifiable);

        if (empty($data)) {
            return false;
        }

        $data['to'] = $expo_token;

        $item = [
            'type' => $notifiable::class,
            'id' => $notifiable->getKey()
        ];

        return ExpoPush::send($data, $item);

        // Send notification to the $notifiable instance...
    }
}
