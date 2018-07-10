<?php

namespace ZanySoft\Widgets\Test\Dummies;

use ZanySoft\Widgets\AbstractWidget;

class TestDefaultSlider extends AbstractWidget
{
    protected $slides = 6;

    public function run()
    {
        return 'Default test slider was executed with $slides = '.$this->slides;
    }
}
