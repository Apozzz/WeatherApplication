<?php

namespace AdeoWeb\WeatherConditions\Api;

use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface;
use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface WeatherTypeRepositoryInterface
{
    /**
     * @param \AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface $weatherType
     * @return \AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface
     */
    public function save(\AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface $weatherType);

    /**
     * @param int $weatherTypeId
     * @return \AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface
     */
    public function getById(int $weatherTypeId);

    /**
     * @param string $weatherTypeName
     * @return \AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface
     */
    public function getByName(string $weatherTypeName);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \AdeoWeb\WeatherConditions\Api\Data\WeatherTypeSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param \AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface $weatherType
     * @return bool
     */
    public function delete(\AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface $weatherType);

    /**
     * @param int $weatherTypeId
     * @return bool
     */
    public function deleteById(int $weatherTypeId);
}
