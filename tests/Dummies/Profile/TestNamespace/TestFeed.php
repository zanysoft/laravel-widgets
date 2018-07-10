<?php

namespace ZanySoft\Widgets\Test\Dummies\Profile\TestNamespace;

use ZanySoft\Widgets\AbstractWidget;

class TestFeed extends AbstractWidget
{
    protected $slides = 6;

    public function run()
    {
        return 'Feed was executed with $slides = '.$this->slides;
    }
}
