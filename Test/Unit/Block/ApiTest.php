<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Test\Unit\Block;

use AdeoWeb\WeatherApplication\Model\ResourceModel\WeatherType\Collection;
use AdeoWeb\WeatherApplication\Model\ResourceModel\WeatherType\CollectionFactory;
use AdeoWeb\WeatherApplication\Block\Api;
use ArrayObject;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    private $collectionFactory;

    private $object;

    private $collection;

    protected function setUp()
    {
        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['load', 'getId', 'getName'])
            ->getMock();

        $context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new Api(
            $this->collectionFactory,
            $context
        );
    }

    protected function tearDown()
    {
        $this->collectionFactory = null;
        $this->collection = null;
        $this->object = null;
    }

    public function testGetWeatherTypes()
    {
        $dat = new DataObject();
        $dat->addData(['id' => 1, 'name' => 'Sunny']);
        $this->collectionFactory->expects($this->once())->method('create')->willReturn($this->collection);
        $this->collection->expects($this->once())->method('load')->willReturn($dat->getData());
        $this->collection->expects($this->once())->method('getId')->willReturn(1);
        $this->collection->expects($this->once())->method('getName')->willReturn('Sunny');
        $this->assertInstanceOf(ArrayObject::class, $this->object->getWeatherTypes());
    }
}
