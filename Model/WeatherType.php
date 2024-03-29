<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model;

use AdeoWeb\WeatherApplication\Api\Data\WeatherTypeInterface;
use Magento\Framework\Model\AbstractModel;

class WeatherType extends AbstractModel implements WeatherTypeInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\WeatherType::class);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * @param int $id
     * @return WeatherTypeInterface|WeatherType
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function setName(string $name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function setCreationTime(string $creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    public function setUpdateTime(string $updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }
}
