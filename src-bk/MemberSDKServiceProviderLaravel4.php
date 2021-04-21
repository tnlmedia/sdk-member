<?php

namespace Tnlmedia\MemberSDK;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response as IlluminateResponse;

class MemberSDKServiceProviderLaravel4 extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('tnlmedia/member-sdk');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app['member-sdk'] = $app->share(function ($app) {
            return new MemberSDK($app['config']->get('member-sdk::config'));
        });

        $app->alias('member-sdk', 'TnlMedia\MemberSDK\MemberSDK');
    }
}
