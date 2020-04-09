<?php

namespace NotificationChannels\Intercom\Tests;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Intercom\IntercomChannel;
use NotificationChannels\Intercom\IntercomServiceProvider;
use NotificationChannels\Intercom\Tests\Mocks\TestConfigRepository;
use NotificationChannels\Intercom\Tests\Mocks\TestFakeApplication;
use PHPUnit\Framework\TestCase;

class IntercomServiceProviderTest extends TestCase
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var IntercomServiceProvider
     */
    private $serviceProvider;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app = new TestFakeApplication();
        $this->app['config'] = new TestConfigRepository();
        Config::setFacadeApplication($this->app);
        $this->serviceProvider = new IntercomServiceProvider($this->app);

        Notification::swap(new ChannelManager($this->app));

        $this->serviceProvider->boot();
    }

    public function testItRegistersNewIntercomNotificationDriverAlias(): void
    {
        $this->serviceProvider->register();

        self::assertInstanceOf(IntercomChannel::class, Notification::driver('intercom'));
    }
}
