<?php namespace Notify;

use Illuminate\Support\ServiceProvider;

class NotifyServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('notify', function()
        {
            return new Notify;
        });
    }

}