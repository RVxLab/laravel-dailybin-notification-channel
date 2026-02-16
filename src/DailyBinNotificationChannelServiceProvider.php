<?php

declare(strict_types=1);

namespace RVxLab\DailyBinNotificationChannel;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\{Http, Notification};
use Illuminate\Support\ServiceProvider;
use RVxLab\DailyBinNotificationChannel\Channels\DailyBinChannel;

final class DailyBinNotificationChannelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Notification::resolved(function (ChannelManager $service): void {
            $service->extend('dailyBin', fn (): DailyBinChannel => new DailyBinChannel());
        });
    }

    public function boot(): void
    {
        Http::macro(
            'dailyBin',
            function (): PendingRequest {
                /** @var string $token */
                $token = config('service.dailybin.token', '');

                return Http::baseUrl('https://dailybin.dev/api/v1')
                    ->contentType('application/json')
                    ->accept('application/json')
                    ->withToken($token);
            },
        );
    }
}
