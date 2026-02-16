<?php

declare(strict_types=1);

namespace RVxLab\DailyBinNotificationChannel\Actions;

use Illuminate\Support\Facades\Validator;
use RVxLab\DailyBinNotificationChannel\Messages\DailyBinMessage;

/**
 * @internal
 */
final class ValidateMessage
{
    public function __invoke(DailyBinMessage $message): void
    {
        Validator::make($message->toArray(), [
            'section' => [
                'required',
                'string',
                'max:64',
            ],
            'content' => [
                'required',
                'string',
                'max:20000', // 20kb
            ],
            'source' => [
                'nullable',
                'string',
                'between:1,80',
            ],
        ])->validate();
    }
}
