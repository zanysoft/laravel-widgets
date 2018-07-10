<?php

namespace ZanySoft\Widgets\Test\Dummies;

use ZanySoft\Widgets\AbstractWidget;

class Slider extends AbstractWidget
{
    protected $config = [
        'slides' => 6,
        'foo'    => 'bar',
    ];

    public function run()
    {
        return 'Slider was executed with $slides = '.$this->config['slides'].' foo: '.$this->config['foo'];
    }

    public function placeholder()
    {
        return 'Placeholder here!';
    }
}
