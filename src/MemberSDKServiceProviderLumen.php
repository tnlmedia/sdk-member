<?php

namespace Tnlmedia\MemberSDK;

use Illuminate\Support\ServiceProvider;

class MemberSDKServiceProviderLumen extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        // merge default config
        $this->mergeConfigFrom(
            __DIR__.'/config.php',
            'member-sdk'
        );

        // set configuration
        $app->configure('member-sdk');

        // create image
        $app->singleton('member-sdk',function ($app) {
            return new MemberSDK($app['config']->get('member-sdk'));
        });

        $app->alias('member-sdk', 'Tnlmedia\MemberSDK\MemberSDK');
    }
}
