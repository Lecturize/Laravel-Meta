<?php namespace vendocrat\Meta\Facades;

use Illuminate\Support\Facades\Facade;

class Meta extends Facade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return 'meta';
    }
}