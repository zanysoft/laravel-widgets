<?php

namespace ZanySoft\Widgets\Misc;

use Exception;

class InvalidWidgetClassException extends Exception
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'Widget class must extend ZanySoft\Widgets\AbstractWidget class';
}
