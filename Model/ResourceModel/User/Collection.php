<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model\ResourceModel\User;

use AdeoWeb\WeatherApplication\Model\User;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'user_id';

    protected function _construct()
    {
        $this->_init(User::class, \AdeoWeb\WeatherApplication\Model\ResourceModel\User::class);
    }
}
