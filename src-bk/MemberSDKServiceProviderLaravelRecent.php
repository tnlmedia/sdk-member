<?php

namespace Tnlmedia\MemberSDK;

use Illuminate\Support\ServiceProvider;

class MemberSDKServiceProviderLaravelRecent extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $this->mergeConfigFrom(
            __DIR__.'/config.php',
            'member-sdk'
        );
        $app->singleton('member-sdk',function($app){
            return new MemberSDK($this->getMemberSDKConfig($app));
        });

        $app->alias('member-sdk', 'Tnlmedia\MemberSDK\MemberSDK');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('member-sdk.php')
        ]);
    }

    private function getMemberSDKConfig($app)
    {
        $config = $app['config']->get('member-sdk');

        if (is_null($config)) {
            return [];
        }

        return $config;
    }
}
