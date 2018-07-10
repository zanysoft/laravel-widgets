<?php

namespace ZanySoft\Widgets\Test\Dummies;

use ZanySoft\Widgets\AbstractWidget;

class TestRepeatableFeed extends AbstractWidget
{
    protected $slides = 6;

    /**
     * The number of seconds before reload from server.
     *
     * @var float|int
     */
    public $reloadTimeout = 10;

    public function run()
    {
        return 'Feed was executed with $slides = '.$this->slides;
    }
}
