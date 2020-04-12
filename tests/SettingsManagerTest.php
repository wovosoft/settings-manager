<?php

namespace Wovosoft\SettingsManager\Tests;

use Orchestra\Testbench\TestCase;
use Wovosoft\SettingsManager\Facades\SettingsManager;
use Wovosoft\SettingsManager\ServiceProvider;

class SettingsManagerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'settings-manager' => SettingsManager::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
