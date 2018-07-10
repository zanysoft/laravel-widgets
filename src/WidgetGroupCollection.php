<?php

namespace ZanySoft\Widgets;

use ZanySoft\Widgets\Contracts\ApplicationWrapperContract;
use Illuminate\Support\Str;

class WidgetGroupCollection
{
    /**
     * The array of widget groups.
     *
     * @var array
     */
    protected $groups;

    /**
     * Constructor.
     *
     * @param ApplicationWrapperContract $app
     */
    public function __construct(ApplicationWrapperContract $app)
    {
        $this->app = $app;
    }

    /**
     * Get the widget group object.
     *
     * @param $name
     *
     * @return WidgetGroup
     */
    public function group($name)
    {
        if (isset($this->groups[$name])) {
            return $this->groups[$name];
        }

        $this->groups[$name] = new WidgetGroup($name, $this->app);

        return $this->groups[$name];
    }
    
    /**
     * Get the widget group object.
     *
     * @param $name
     *
     * @return WidgetGroup
     */
    public function groups($name = '') {

        if (Str::endsWith($name, '*')) {

            return array_filter($this->groups, function ($a, $b) use ($name) {
                if (Str::is($name, $b)) {
                    return $a;
                }
            }, ARRAY_FILTER_USE_BOTH);
        }

        if (isset($this->groups[$name])) {
            return $this->groups[$name];
        }

        if (empty($name)) {
            return $this->groups;
        }
    }
}
