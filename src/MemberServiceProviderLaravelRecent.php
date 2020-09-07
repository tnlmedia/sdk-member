<?php

namespace Tnlmedia\MemberSDK;

use Illuminate\Support\ServiceProvider;

class MemberServiceProviderLaravelRecent extends ServiceProvider
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
            'member'
        );
        $app->singleton('member',function($app){
            return new Member($this->getMemberConfig($app));
        });

        $app->alias('memeber', 'Tnlmedia\MemberSDK\Member');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('member.php')
        ]);

    }

    private function getMemberConfig($app)
    {
        $config = $app['config']->get('member');

        if (is_null($config)) {
            return [];
        }

        return $config;
    }
}
