<?php

namespace AdeoWeb\WeatherApplication\Api\Data;

interface UserInterface
{
    public const ID             = 'user_id';
    public const NAME           = 'name';
    public const SURNAME        = 'surname';
    public const BIRTH_DATE     = 'birth_date';
    public const WEATHER_TYPE   = 'weather_id';
    public const CREATION_TIME  = 'created_at';
    public const UPDATE_TIME    = 'updated_at';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSurname();

    /**
     * @return string
     */
    public function getBirthDate();

    /**
     * @return int
     */
    public function getWeatherType();

    /**
     * @return string|null
     */
    public function getCreationTime();

    /**
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * @param int $id
     * @return UserInterface
     */
    public function setId(int $id);

    /**
     * @param string $name
     * @return UserInterface
     */
    public function setName(string $name);

    /**
     * @param string $surname
     * @return UserInterface
     */
    public function setSurname(string $surname);

    /**
     * @param string $birthDate
     * @return UserInterface
     */
    public function setBirthDate(string $birthDate);

    /**
     * @param int $weatherType
     * @return UserInterface
     */
    public function setWeatherType(int $weatherType);

    /**
     * @param string $creationTime
     * @return UserInterface
     */
    public function setCreationTime(string $creationTime);

    /**
     * @param string $updateTime
     * @return UserInterface
     */
    public function setUpdateTime(string $updateTime);
}
