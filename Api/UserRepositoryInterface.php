<?php

namespace AdeoWeb\WeatherApplication\Api;

interface UserRepositoryInterface
{
    /**
     * @param int $userId
     * @return \AdeoWeb\WeatherApplication\Api\Data\UserInterface
     */
    public function getById(int $userId);

    /**
     * @param int $userId
     * @param int $weatherTypeId
     * @return bool
     */
    public function saveById(int $userId, int $weatherTypeId);
}
