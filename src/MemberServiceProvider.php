<?php

namespace Tnlmedia\Member;

use Illuminate\Support\ServiceProvider;

class MemberServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('member',function(){
            return new Member();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
