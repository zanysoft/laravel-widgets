<?php

namespace ZanySoft\Widgets\Test;

use ZanySoft\Widgets\Test\Support\TestApplicationWrapper;
use ZanySoft\Widgets\Test\Support\TestCase;
use ZanySoft\Widgets\WidgetGroup;
use ZanySoft\Widgets\WidgetGroupCollection;

class WidgetGroupCollectionTest extends TestCase
{
    /**
     * @var WidgetGroupCollection
     */
    protected $collection;

    public function setUp()
    {
        $this->collection = new WidgetGroupCollection(new TestApplicationWrapper());
    }

    public function testItGrantsAccessToWidgetGroup()
    {
        $groupObject = $this->collection->group('sidebar');

        $expectedObject = new WidgetGroup('sidebar', new TestApplicationWrapper());

        $this->assertEquals($expectedObject, $groupObject);
    }
}
