<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class User extends AbstractDb
{
    private const TABLE_NAME    = 'adeoweb_weatherapplication_user';
    private const FIELD_NAME_ID = 'user_id';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::FIELD_NAME_ID);
    }
}
