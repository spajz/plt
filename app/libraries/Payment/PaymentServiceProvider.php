<?php namespace PaymentGateway;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('payment', function()
        {
            return new Payment;
        });
    }

}