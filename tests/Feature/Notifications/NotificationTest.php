<?php

declare(strict_types=1);

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\{Http, Notification as NotificationFacade};
use RVxLab\DailyBinNotificationChannel\Channels\DailyBinChannel;
use RVxLab\DailyBinNotificationChannel\Messages\DailyBinMessage;

beforeEach(function (): void {
    Http::preventStrayRequests();
});

it('can send a notification', function (): void {
    Http::fake([
        'https://dailybin.dev/api/v1/mail' => Http::response([
            'accepted' => true,
            'duplicate' => false,
            'digestDate' => '2026-02-15',
            'submissionId' => 'sub_abcdefghijklm',
        ], 202),
    ]);

    NotificationFacade::route('dailybin', 'test')
        ->notify(new TestNotification());

    Http::assertSentCount(1);

    [$request, $response] = Http::recorded()[0];

    $data = json_decode((string) $request->body(), true, flags: JSON_THROW_ON_ERROR);

    expect($request->url())->toBe('https://dailybin.dev/api/v1/mail')
        ->and($data['section'])->toBe('test')
        ->and($data['content'])->toBe("# Test Notification\n\nThis is a test notification!")
        ->and($data['source'])->toBe('Unit Tests')
        ->and($request->method())->toBe('POST')
        ->and($response->status())->toBe(202)
        ->and($response->json('submissionId'))->toBe('sub_abcdefghijklm');
});

/**
 * @internal
 * @noinspection PhpIllegalPsrClassPathInspection
 */
final class TestNotification extends Notification
{
    public function via(mixed $notifiable): array
    {
        return [DailyBinChannel::class];
    }

    public function toDailyBin(mixed $notifiable): DailyBinMessage
    {
        return (new DailyBinMessage())
            ->section('test')
            ->content("# Test Notification\n\nThis is a test notification!")
            ->source('Unit Tests');
    }
}
