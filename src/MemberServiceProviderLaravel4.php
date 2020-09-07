<?php

namespace Tnlmedia\MemberSDK;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response as IlluminateResponse;

class MemberServiceProviderLaravel4 extends ServiceProvider
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

        $app['member'] = $app->share(function ($app) {
            return new Member($app['config']->get('member::config'));
        });

        $app->alias('member', 'TnlMedia\MemberSDK\Member');
    }
}
