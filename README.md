# Laravel Expo Push Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/trin4ik/laravel-expo-push.svg?style=flat-square)](https://packagist.org/packages/trin4ik/laravel-expo-push)
[![Total Downloads](https://img.shields.io/packagist/dt/trin4ik/laravel-expo-push.svg?style=flat-square)](https://packagist.org/packages/trin4ik/laravel-expo-push)
![GitHub Actions](https://github.com/trin4ik/laravel-expo-push/actions/workflows/main.yml/badge.svg)

Simple and stupid package for send push notification with [expo-notification](https://docs.expo.io/versions/latest/sdk/notifications/) [service](https://docs.expo.io/push-notifications/sending-notifications/). \
No tests, no mans, no routes/controllers for get tokens from clients etc. Only channel. \
If u need more, use [Alymosul/laravel-exponent-push-notifications](https://github.com/Alymosul/laravel-exponent-push-notifications)

## Installation

```bash
composer require trin4ik/laravel-expo-push
php artisan vendor:publish --provider "Trin4ik\LaravelExpoPush\ExpoPushServiceProvider"
```
U can log query to expo notification service into database with payloads and responses. 

```bash
echo "EXPO_PUSH_LOG=true" >> .env
php artisan migrate
```
### Users expo tokens

U need add method `routeNotificationForExpoPush` any model, like `User`, who return expo token

```php
<?php

namespace App\Models;

// ...

class User extends Authenticatable
{
    use Notifiable;

    // ...

    public function routeNotificationForExpoPush () {
        return $this->expo_token; // like ExponentPushToken[XXXXXXX_XXXXXXXXXXXXXX]
    }
}

```

### Change log database
By default, log write to `expo_push_notification` table, u can change it in 2 step: 

1. extends `Trin4ik\LaravelExpoPush\Models\ExpoPushNotification.php` like:

```php
<?php
namespace App\Models;

class ExpoPushNotification extends Trin4ik\LaravelExpoPush\Models\ExpoPushNotification {
    protected $table = 'my_custom_notifications_table';
}
```

2. change in `config/expo-push.php` `log/driver/instance` to your new model:
```php
return [
    // ...
    'log' => [
        // ...
        'driver' => [
            // ...
            'instance' => \App\Models\ExpoPushNotification::class
        ]
    ]
];
```

## Usage

Like other channels, u need create `Notification`

```bash
php artisan make:notification PushTest
```

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Trin4ik\LaravelExpoPush\Channels\ExpoPushChannel;
use Trin4ik\LaravelExpoPush\ExpoPush;

class PushTest extends Notification implements ShouldQueue
{
    use Queueable;
    
    public function via($notifiable)
    {
        return [ExpoPushChannel::class];
    }

    public function toExpoPush($notifiable) {
        return ExpoPush::create()
            ->badge(1)
            ->title("Congratulations!")
            ->body("Your " . $notifiable->email . " account was approved!");
    }
}
```
> for more information about `ExpoPush::create` methods, [look down](#methods)

And usage like:

```php
<?php
use App\Notifications\PushTest;
use App\Models\User;

// ...

$user = User::find(1);
$user->notify(new PushTest);
```

## <a name="methods"></a>`ExpoPush::create` methods 

| Method        | Type          | Default  | Platform | Description |
| ------------- | ------------- | -------- | -------- | ----------- |
| data | `array` | `null` | iOS & Android | Any data to your app on click to push notification. Array converted to JSON, JSON may be up to about 4KiB |
| title | `string` | `null` | iOS & Android | Notification title |
| body | `string` | `null` | iOS & Android | Notification text |
| ttl | `int` | Android: `2419200` <br /> iOS: `0` | iOS & Android | TimeToLive, in seconds. Need to redelivery push |
| expiration | `int` | `null` | iOS & Android | Timestamp to expires message. Like `ttl`, but other format. <br /> `ttl` takes precedence over `expiration` |
| priority | `enum(default, normal, high)` | `default` | iOS & Android | Delivery priority |
| categoryId | `string` | `null` | iOS & Android | ID of the notification category that this notification is associated with. Find out more about notification categories here. Must be on at least SDK 41 or bare workflow |
| subtitle | `string` | `null` | iOS Only | Notification subtitle |
| sound | `bool` | `true` | iOS Only | Play sound when the recipient receives this notification |
| badge | `int` | `null` | iOS Only | Number to display in the badge on the app icon. Specify zero to clear the badge |
| mutableContent | `bool` | `null` | iOS Only | Specifies whether this notification can be intercepted by the client app. In Expo Go, this defaults to `true`, and if you change that to `false`, you may experience issues. In standalone and bare apps, this defaults to `false` |
| channelId | `string` | `null` | Android Only | ID of the Notification Channel through which to display this notification. If an ID is specified but the corresponding channel does not exist on the device (i.e. has not yet been created by your app), the notification will not be displayed to the user |
> More info on [Expo notification service message format](https://docs.expo.io/push-notifications/sending-notifications/#message-request-format)

If new SDK release new format, u can create PR or use `toArray()` method in your Notification, like:

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Trin4ik\LaravelExpoPush\Channels\ExpoPushChannel;

class PushTest extends Notification implements ShouldQueue
{
    use Queueable;
    
    public function via($notifiable)
    {
        return [ExpoPushChannel::class];
    }

    public function toArray($notifiable) {
        return [
            'badge' => 1,
            'title' => "Congratulations!",
            'body' => "Your " . $notifiable->email . " account was approved!",
            'new_expo_notification_param' => true
        ];
    }
}
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email trin4ik@gmail.com instead of using the issue tracker.

## Credits

-   [trin4ik](https://github.com/trin4ik)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Thanks

❤️ This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com). \
❤️ More code spizjeno from [Alymosul/laravel-exponent-push-notifications](https://github.com/Alymosul/laravel-exponent-push-notifications)
