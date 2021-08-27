<?php

namespace AdeoWeb\WeatherApplication\Api;

interface WeatherTypeRepositoryInterface
{
    /**
     * @param int $weatherTypeId
     * @return \AdeoWeb\WeatherApplication\Api\Data\WeatherTypeInterface
     */
    public function getById(int $weatherTypeId);
}
