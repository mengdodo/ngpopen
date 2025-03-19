<?php

namespace Mengdodo\Ngpopen\Facades;

use Illuminate\Support\Facades\Facade;

class NgpOpenFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'NgpOpen';
    }
}
