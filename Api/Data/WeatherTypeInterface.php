<?php

namespace AdeoWeb\WeatherConditions\Api\Data;

interface WeatherTypeInterface
{
    public const ID             = 'id';
    public const STATUS         = 'status';
    public const NAME           = 'name';
    public const ALIAS          = 'alias';
    public const ICON           = 'icon';
    public const DESCRIPTION    = 'description';
    public const CREATION_TIME  = 'created_at';
    public const UPDATE_TIME    = 'updated_at';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string|null
     */
    public function getAlias();

    /**
     * @return string|null
     */
    public function getIcon();

    /**
     * @return string|null
     */
    public function getDescription();

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
     * @param int $status
     * @return WeatherTypeInterface
     */
    public function setStatus(int $status);

    /**
     * @param string $name
     * @return WeatherTypeInterface
     */
    public function setName(string $name);

    /**
     * @param string $alias
     * @return WeatherTypeInterface
     */
    public function setAlias(string $alias);

    /**
     * @param string $icon
     * @return WeatherTypeInterface
     */
    public function setIcon(string $icon);

    /**
     * @param string $description
     * @return WeatherTypeInterface
     */
    public function setDescription(string $description);

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
