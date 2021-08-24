<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class WeatherType extends AbstractDb
{
    private const TABLE_NAME = 'adeoweb_weatherapplication_weathertype';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'weathertype_id');
    }
}
