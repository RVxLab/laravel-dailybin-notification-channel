<?php

declare(strict_types=1);

use Illuminate\Notifications\{Notifiable, Notification};
use Illuminate\Support\Facades\{Http, Notification as NotificationFacade};
use RVxLab\DailyBinNotificationChannel\Messages\DailyBinMessage;

beforeEach(function (): void {
    Http::preventStrayRequests();
});

it('can send a notification via an anonymous notification', function (): void {
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

it('can send a notification via a notifiable object', function (): void {
    $user = new class {
        use Notifiable;

        public function routeNotificationForDailyBin(): string
        {
            return 'test';
        }
    };


    Http::fake([
        'https://dailybin.dev/api/v1/mail' => Http::response([
            'accepted' => true,
            'duplicate' => false,
            'digestDate' => '2026-02-15',
            'submissionId' => 'sub_abcdefghijklm',
        ], 202),
    ]);

    $user->notify(new TestNotification());

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
        return ['dailyBin'];
    }

    public function toDailyBin(mixed $notifiable): DailyBinMessage
    {
        return (new DailyBinMessage())
            ->section('test')
            ->content("# Test Notification\n\nThis is a test notification!")
            ->source('Unit Tests');
    }
}
