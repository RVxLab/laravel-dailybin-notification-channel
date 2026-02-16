<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Config\Repository;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RVxLab\DailyBinNotificationChannel\DailyBinNotificationChannelServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            DailyBinNotificationChannelServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        tap($app['config'], function (Repository $config): void {
            $config->set('service.dailybin.token', 'testtoken');
        });
    }
}
