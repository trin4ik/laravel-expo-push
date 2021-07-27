<?php
namespace Trin4ik\LaravelExpoPush\Drivers;

use Trin4ik\LaravelExpoPush\Exceptions\LogModelNullInstance;

class ExpoPushNotificationDriverModel {
    public function create (array $data, $instance) {
        if (!$instance || !class_exists($instance)) {
            throw new LogModelNullInstance('u need provide model instance');
        }

        try {
            $body = json_decode($data['response']['body'], true);

            $instance::create([
                'item_type' => $data['item']['type'],
                'item_id' => $data['item']['id'],
                'payload' => $data['payload'],
                'response' => $body,
                'code' => $data['response']['code'],
                'status' => isset($body['errors']) || $body['data']['status'] === 'error' ? 'error' : 'ok',
            ]);
        } catch (\Exception $e) {
            throw new LogModelNullInstance('cant create log model', $e->getMessage());
        }
    }
}
