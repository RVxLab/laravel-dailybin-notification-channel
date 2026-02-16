<?php

declare(strict_types=1);

use Illuminate\Validation\ValidationException;
use RVxLab\DailyBinNotificationChannel\Actions\ValidateMessage;
use RVxLab\DailyBinNotificationChannel\Messages\DailyBinMessage;

it('can validation a message', function (): void {
    $message = (new DailyBinMessage())
        ->section('section')
        ->content("# Content\n\nIn Markdown!");

    (new ValidateMessage())($message);
})->throwsNoExceptions();

it('can validate a message with a source', function (): void {
    $message = (new DailyBinMessage())
        ->section('section')
        ->content("# Content\n\nIn Markdown!")
        ->source('Unit Tests');

    (new ValidateMessage())($message);
})->throwsNoExceptions();

it('can fail validation when the section is absent', function (): void {
    $message = (new DailyBinMessage())
        ->content("# Content\n\nIn Markdown!")
        ->source('Unit Tests');

    (new ValidateMessage())($message);
})->throws(ValidationException::class, 'The section field is required.');

it('can fail validation when the section is too long', function (): void {
    $message = (new DailyBinMessage())
        ->section(str_repeat('a', 65))
        ->content("# Content\n\nIn Markdown!")
        ->source('Unit Tests');

    (new ValidateMessage())($message);
})->throws(ValidationException::class, 'The section field must not be greater than 64 characters.');

it('can fail validation when the content is absent', function (): void {
    $message = (new DailyBinMessage())
        ->section('section')
        ->source('Unit Tests');

    (new ValidateMessage())($message);
})->throws(ValidationException::class, 'The content field is required.');

it('can fail validation when the content is too long', function (): void {
    $message = (new DailyBinMessage())
        ->section('section')
        ->content(str_repeat('a', 20001))
        ->source('Unit Tests');

    (new ValidateMessage())($message);
})->throws(ValidationException::class, 'The content field must not be greater than 20000 characters.');

it('can fail validation when the source is too long', function (): void {
    $message = (new DailyBinMessage())
        ->section('section')
        ->content("# Content\n\nIn Markdown!")
        ->source(str_repeat('a', 81));

    (new ValidateMessage())($message);
})->throws(ValidationException::class, 'The source field must be between 1 and 80 characters.');
