<?php

namespace App\Channels;

use App\Models\UserDevice;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FcmChannel
{
    public function __construct(
        protected Messaging $messaging
    ) {
    }

    public function send(
        object $notifiable,
        Notification $notification
    ): void {
        $devices = UserDevice::where(
            'user_id',
            $notifiable->id
        )->get();

        if ($devices->isEmpty()) {
            return;
        }

        if (! method_exists($notification, 'toDatabase')) {
            return;
        }

        $data = $notification->toDatabase($notifiable);

        $firebaseNotification = FirebaseNotification::create(
            $data['title'] ?? 'School Management System',
            $data['message'] ?? ''
        );


        $firebaseData = [];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $firebaseData[$key] = (string) $value;
            }
        }


        foreach ($devices as $device) {
            try {
                $message = CloudMessage::new()
                    ->withToken($device->device_token)
                    ->withNotification($firebaseNotification)
                    ->withData($firebaseData);

                $this->messaging->send($message);

            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}