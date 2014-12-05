<?php namespace Notify\Facades;

use Illuminate\Support\Facades\Facade;

class Notify extends Facade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor()
    {
        return 'notify';
    }
}
