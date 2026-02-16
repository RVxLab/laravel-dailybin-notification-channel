# Laravel Notification Channel for Daily Bin

A notification channel for [Daily Bin](https://dailybin.dev) by [Chris Arter](https://github.com/christopherarter).

## Prerequisites

- An account on [Daily Bin](https://dailybin.dev)
- A token with at least the following scopes:
    - `ingest:write`

## Installation

Install using Composer:

```shell
composer require rvxlab/laravel-notification-channel-dailybin
```

Add this to your `config/services.php`:

```php
'dailybin' => [
    'token' => env('DAILYBIN_TOKEN'),
],
```

Set your `DAILYBIN_TOKEN` in your `.env` file:

```shell
DAILYBIN_TOKEN=YOUR TOKEN GOES HERE
```

### Setting Up Your Notification

Add the Daily Bin channel to your notification and set up a `toDailyBin` method:

```php
class SomeNotification extends Notification
{
    public function via($notifiable)
    {
        return [DailyBinChannel::class]; // or ['dailyBin']
    }
    
    public function toDailyBin($notifiable)
    {
        return (new DailyBinMessage())
            ->section('content')
            ->content('# Hello, world!')
            ->source('My App'); // Optional
    }
}
```

Then either make use of anonymous notification or register a notification route:

```php
use Illuminate\Support\Facades\Notification;

Notification::route('dailyBin', 'whatever you like')
    ->notify(new SomeNotification());

// OR

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    public function routeNotificationForDailyBin(): string
    {
        return 'whatever you like'; // or false if you don't want to send notifications
    }
}

$user = User::firstOrFail();
$user->notify(new SomeNotification());
```

## Contributing

Contributions are very welcome. Please read [CONTRIBUTING.md](./CONTRIBUTING.md) for guidelines.

## License

This package is licensed under the MIT License.
