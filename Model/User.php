<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model;

use AdeoWeb\WeatherApplication\Api\Data\UserInterface;
use Magento\Framework\Model\AbstractModel;

class User extends AbstractModel implements UserInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\User::class);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function getSurname()
    {
        return $this->getData(self::SURNAME);
    }

    public function getBirthDate()
    {
        return $this->getData(self::BIRTH_DATE);
    }

    public function getWeatherType()
    {
        return $this->getData(self::WEATHER_TYPE);
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

    public function setSurname(string $surname)
    {
        return $this->setData(self::SURNAME, $surname);
    }

    public function setBirthDate(string $birthDate)
    {
        return $this->setData(self::BIRTH_DATE, $birthDate);
    }

    public function setWeatherType(int $weatherType)
    {
        return $this->setData(self::WEATHER_TYPE, $weatherType);
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
