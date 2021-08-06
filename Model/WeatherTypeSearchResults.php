<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model;

use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class WeatherTypeSearchResults extends SearchResults implements WeatherTypeSearchResultsInterface
{
}
