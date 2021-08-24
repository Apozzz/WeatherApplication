<?php

namespace AdeoWeb\WeatherApplication\Api\Data;

interface WeatherTypeInterface
{
    public const ID             = 'weathertype_id';
    public const NAME           = 'name';
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
     * @return string|null
     */
    public function getCreationTime();

    /**
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * @param int $id
     * @return WeatherTypeInterface
     */
    public function setId(int $id);

    /**
     * @param string $name
     * @return WeatherTypeInterface
     */
    public function setName(string $name);

    /**
     * @param string $creationTime
     * @return WeatherTypeInterface
     */
    public function setCreationTime(string $creationTime);

    /**
     * @param string $updateTime
     * @return WeatherTypeInterface
     */
    public function setUpdateTime(string $updateTime);
}