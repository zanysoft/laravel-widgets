<?php

namespace ZanySoft\Widgets;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'zanysoft.widget';
    }

    /**
     * Get the widget group object.
     *
     * @param $name
     *
     * @return WidgetGroup
     */
    public static function group($name)
    {
        return app('zanysoft.widget-group-collection')->group($name);
    }
    
    /**
     * Get the widget group object.
     *
     * @param $name
     *
     * @return WidgetGroup
     */
    public static function groups($name='')
    {
        return app('zanysoft.widget-group-collection')->groups($name);
    }
}
