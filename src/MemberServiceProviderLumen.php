<?php

namespace Tnlmedia\Member;

use Illuminate\Support\ServiceProvider;

class MemberServiceProviderLumen extends ServiceProvider
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
            'member'
        );

        // set configuration
        $app->configure('member');

        // create image
        $app->singleton('member',function ($app) {
            return new Member($app['config']->get('member'));
        });

        $app->alias('memeber', 'Tnlmedia\Member\Member');
    }
}
