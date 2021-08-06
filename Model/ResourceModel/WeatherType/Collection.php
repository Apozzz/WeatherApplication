<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model\ResourceModel\WeatherType;

use AdeoWeb\WeatherConditions\Model\WeatherType;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(WeatherType::class, \AdeoWeb\WeatherConditions\Model\ResourceModel\WeatherType::class);
    }
}
