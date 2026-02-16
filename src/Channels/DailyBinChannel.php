<?php

declare(strict_types=1);

namespace RVxLab\DailyBinNotificationChannel\Channels;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use RVxLab\DailyBinNotificationChannel\Messages\DailyBinMessage;
use Webmozart\Assert\Assert;

class DailyBinChannel
{
    public function send(mixed $notifiable, Notification $notification): mixed
    {
        if (!is_object($notifiable)) {
            return null;
        }

        if (!method_exists($notifiable, 'routeNotificationFor')) {
            return null;
        }

        $route = $notifiable->routeNotificationFor('dailyBin', $notification);

        if (false === $route) {
            return null;
        }

        if (!method_exists($notification, 'toDailyBin')) {
            return null;
        }

        $message = $notification->toDailyBin($notifiable);

        /** @noinspection PhpConditionAlreadyCheckedInspection */
        Assert::isInstanceOf($message, DailyBinMessage::class);

        /**
         * @var PendingRequest $http
         *
         * @phpstan-ignore method.notFound (this is a macro)
         */
        $http = Http::dailyBin();

        return $http
            ->post('/mail', $message)
            ->throw();
    }
}
