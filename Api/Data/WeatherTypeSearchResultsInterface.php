<?php

namespace AdeoWeb\WeatherConditions\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface WeatherTypeSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface[]
     */
    public function getItems();

    /**
     * @param \AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
