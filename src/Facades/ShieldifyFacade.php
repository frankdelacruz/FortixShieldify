<?php
namespace Fortix\Shieldify\Facades;

use Illuminate\Support\Facades\Facade;

class ShieldifyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'shieldify';
    }
}
