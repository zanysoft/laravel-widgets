<?php

namespace ZanySoft\Widgets;

class AsyncFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'zanysoft.async-widget';
    }
}
