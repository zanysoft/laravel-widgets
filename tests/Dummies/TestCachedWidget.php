<?php

namespace ZanySoft\Widgets\Test\Dummies;

use ZanySoft\Widgets\AbstractWidget;

class TestCachedWidget extends AbstractWidget
{
    public $cacheTime = 60;

    public $cacheTags = ['test'];

    protected $slides = 6;

    public function run()
    {
        return 'Feed was executed with $slides = '.$this->slides;
    }
}
