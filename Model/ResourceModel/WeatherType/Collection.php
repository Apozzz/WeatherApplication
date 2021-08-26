<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model\ResourceModel\WeatherType;

use AdeoWeb\WeatherApplication\Model\WeatherType;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'weathertype_id';

    protected function _construct()
    {
        $this->_init(WeatherType::class, \AdeoWeb\WeatherApplication\Model\ResourceModel\WeatherType::class);
    }
}
