<?php

namespace ZanySoft\Widgets\Test\Dummies;

use ZanySoft\Widgets\AbstractWidget;

class Exception extends AbstractWidget
{
    public function run()
    {
        return 'Exception widget was executed instead of predefined php class';
    }
}
