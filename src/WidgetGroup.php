<?php

namespace ZanySoft\Widgets;

use ZanySoft\Widgets\Contracts\ApplicationWrapperContract;
use ZanySoft\Widgets\Misc\ViewExpressionTrait;

class WidgetGroup
{
    use ViewExpressionTrait;

    /**
     * The widget group name.
     *
     * @var string
     */
    protected $name;

    /**
     * The application wrapper.
     *
     * @var ApplicationWrapperContract
     */
    protected $app;

    /**
     * The array of widgets to display in this group.
     *
     * @var array
     */
    protected $widgets = [];

    /**
     * The position of a widget in this group.
     *
     * @var int
     */
    protected $position = 100;

    /**
     * The separator to display between widgets in the group.
     *
     * @var string
     */
    protected $separator = '';

    /**
     * Id that is going to be issued to the next widget when it's added to the group.
     *
     * @var int
     */
    protected $nextWidgetId = 100;

    /**
     * Id that is going to be issued to the widget when it's added to the group.
     *
     * @var int
     */
    protected $widgetId = null;

    /**
     * A callback that defines extra markup that wraps every widget in the group.
     *
     * @var callable
     */
    protected $wrapCallback;

    /**
     * @param $name
     * @param ApplicationWrapperContract $app
     */
    public function __construct($name, ApplicationWrapperContract $app)
    {
        $this->name = $name;

        $this->app = $app;
    }

    public function getWidgets()
    {
        ksort($this->widgets);

        $output = [];
        $index = 0;
        $count = $this->count();

        foreach ($this->widgets as $position => $widgets) {
            foreach ($widgets as $v => $widget) {
                $widget['group'] = $this->name;
                $widget['position'] = $position;
                $widget['html'] = $this->performWrap($this->displayWidget($widget), $index, $count);
                $output[] = $widget;
                //$output[] =
                /*$index++;
                if ($index !== $count) {
                    $output .= $this->separator;
                }*/
            }
        }

        return $output;
    }

    /**
     * Display all widgets from this group in correct order.
     *
     * @return string
     */
    public function display()
    {
        ksort($this->widgets);

        $output = '';
        $index = 0;
        $count = $this->count();

        foreach ($this->widgets as $position => $widgets) {
            foreach ($widgets as $widget) {
                $output .= $this->performWrap($this->displayWidget($widget), $index, $count);
                $index++;
                if ($index !== $count) {
                    $output .= $this->separator;
                }
            }
        }

        return $this->convertToViewExpression($output);
    }

    /**
     * Remove all widgets with $name from the group.
     *
     * @param string $name
     */
    public function hasWidget($name, $position = null)
    {
        foreach ($this->widgets as $_position => $widgets) {
            if ($position && $_position != $position) {
                continue;
            }
            foreach ($widgets as $i => $widget) {
                if ($widget['arguments'][0] == $name || $widget['name'] == $name) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Remove all widgets with $name from the group.
     *
     * @param string $name
     */
    public function getWidget($id)
    {
        foreach ($this->widgets as $position => $widgets) {
            foreach ($widgets as $i => $widget) {
                if ($widget['id'] === $id) {
                    return $this->widgets[$position][$i];
                }
            }
        }
        return false;
    }

    /**
     * Remove all widgets from the group.
     */
    public function reset()
    {
        $this->widgets = [];

        return $this;
    }

    /**
     * Remove a widget by its id.
     *
     * @param int $id
     */
    public function removeById($id)
    {
        foreach ($this->widgets as $position => $widgets) {
            foreach ($widgets as $i => $widget) {
                if ($widget['id'] === $id) {
                    unset($this->widgets[$position][$i]);

                    return;
                }
            }
        }
    }

    /**
     * Remove all widgets with $name from the group.
     *
     * @param string $name
     */
    public function removeByName($name)
    {
        foreach ($this->widgets as $position => $widgets) {
            foreach ($widgets as $i => $widget) {
                if ($widget['arguments'][0] === $name) {
                    unset($this->widgets[$position][$i]);
                }
            }
        }
    }

    /**
     * Remove all widgets from $position from the group.
     *
     * @param int|string $position
     */
    public function removeByPosition($position)
    {
        if (array_key_exists($position, $this->widgets)) {
            unset($this->widgets[$position]);
        }
    }

    /**
     * Remove all widgets from the group.
     */
    public function removeAll()
    {
        $this->widgets = [];
    }

    /**
     * Set widget id.
     *
     * @param int $position
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->widgetId = $id;

        return $this;
    }

    /**
     * Set widget position.
     *
     * @param int $position
     *
     * @return $this
     */
    public function position($position = null)
    {
        if (!is_null($position)) {
            $this->position = $position;
        }

        return $this;
    }

    /**
     * Add a widget to the group.
     */
    public function addWidget()
    {
        return $this->addWidgetWithType('sync', func_get_args());
    }

    /**
     * Add an async widget to the group.
     */
    public function addAsyncWidget()
    {
        return $this->addWidgetWithType('async', func_get_args());
    }

    /**
     * Getter for position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set a separator to display between widgets in the group.
     *
     * @param string $separator
     *
     * @return $this
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Setter for $this->wrapCallback.
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function wrap(callable $callable)
    {
        $this->wrapCallback = $callable;

        return $this;
    }

    /**
     * Check if there are any widgets in the group.
     *
     * @return bool
     */
    public function any()
    {
        return !$this->isEmpty();
    }

    /**
     * Check if there are no widgets in the group.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->widgets);
    }

    /**
     * Count the number of widgets in this group.
     *
     * @return int
     */
    public function count()
    {
        $count = 0;
        foreach ($this->widgets as $position => $widgets) {
            $count += count($widgets);
        }

        return $count;
    }

    /**
     * Add a widget with a given type to the array.
     *
     * @param string $type
     * @param array $arguments
     *
     * @return int
     */
    protected function addWidgetWithType($type, array $arguments = [])
    {
        if (!isset($this->widgets[$this->position])) {
            $this->widgets[$this->position] = [];
        }

        if ($this->widgetId) {
            $id = $this->widgetId;
        } else {
            $id = $this->nextWidgetId;
        }

        $name = $arguments[0];
        $config = $arguments[1]??[];
        $widget = [
            'id' => $id,
            'group' => $this->name,
            'position' => $this->position,
            'name' => $name,
            'config' => $config,
            'arguments' => $arguments,
            'type' => $type,
        ];

        $this->widgets[$this->position][] = $widget;

        $this->resetPosition();

        if (!$this->widgetId) {
            $this->nextWidgetId++;
        }

        return $id;
    }

    /**
     * Display a widget according to its type.
     *
     * @param $widget
     *
     * @return mixed
     */
    protected function displayWidget($widget)
    {
        $factory = $this->app->make($widget['type'] === 'sync' ? 'zanysoft.widget' : 'zanysoft.async-widget');

        return call_user_func_array([$factory, 'run'], $widget['arguments']);
    }

    /**
     * Reset the position property back to the default.
     * So it does not affect the next widget.
     */
    protected function resetPosition()
    {
        $this->position = 100;
    }

    /**
     * Wraps widget content in a special markup defined by $this->wrap().
     *
     * @param string $content
     * @param int $index
     * @param int $total
     *
     * @return string
     */
    protected function performWrap($content, $index, $total)
    {
        if (is_null($this->wrapCallback)) {
            return $content;
        }

        $callback = $this->wrapCallback;

        return $callback($content, $index, $total);
    }
}
