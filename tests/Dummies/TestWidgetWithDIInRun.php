<?php

namespace ZanySoft\Widgets\Test\Dummies;

use ZanySoft\Widgets\AbstractWidget;

class TestWidgetWithDIInRun extends AbstractWidget
{
    public function run(TestMyClass $class)
    {
        return $class->foo;
    }

    public function placeholder()
    {
        return 'Placeholder here!';
    }
}

class TestMyClass
{
    public $foo = 'bar';
}
