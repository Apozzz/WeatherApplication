<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model;

use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeSearchResultsInterfaceFactory;
use AdeoWeb\WeatherConditions\Model\ResourceModel\WeatherType\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;

class CollectionProcessor
{
    /**
     * @var WeatherTypeSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    public function __construct(
        WeatherTypeSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
    }

    public function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    public function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() === SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    public function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    public function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
