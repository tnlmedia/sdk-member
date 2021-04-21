<?php

namespace Tnlmedia\MemberSDK;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as IlluminateApplication;


class MemberSDKServiceProvider extends ServiceProvider
{
    protected $defer = false;

    protected $provider;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->provider = $this->getProvider();
    }
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        return $this->provider->register();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (method_exists($this->provider, 'boot')) {
            return $this->provider->boot();
        }
    }

    private function getProvider()
    {
        if ($this->app instanceof LumenApplication) {
            $provider = '\Tnlmedia\MemberSDK\MemberSDKServiceProviderLumen';
        } elseif (version_compare(IlluminateApplication::VERSION, '5.0', '<')) {
            $provider = '\Tnlmedia\MemberSDK\MemberSDKServiceProviderLaravel4';
        } else {
            $provider = '\Tnlmedia\MemberSDK\MemberSDKServiceProviderLaravelRecent';
        }

        return new $provider($this->app);
    }

    public function provides()
    {
        return ['member-sdk'];
    }
}
