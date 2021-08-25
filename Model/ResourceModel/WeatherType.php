<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class WeatherType extends AbstractDb
{
    private const TABLE_NAME    = 'adeoweb_weatherapplication_weathertype';
    private const FIELD_NAME_ID = 'weathertype_id';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::FIELD_NAME_ID);
    }
}
