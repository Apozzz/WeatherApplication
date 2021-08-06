<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model;

use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeSearchResultsInterfaceFactory;
use AdeoWeb\WeatherConditions\Api\WeatherTypeRepositoryInterface;
use AdeoWeb\WeatherConditions\Model\ResourceModel\WeatherType as ResourceWeatherType;
use AdeoWeb\WeatherConditions\Model\ResourceModel\WeatherType\CollectionFactory;
use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class WeatherTypeRepository implements WeatherTypeRepositoryInterface
{
    /**
     * @var ResourceWeatherType
     */
    private $resource;

    /**
     * @var WeatherTypeFactory
     */
    private $weatherTypeFactory;

    /**
     * @var CollectionFactory
     */
    private $weatherTypeCollectionFactory;

    /**
     * @var WeatherTypeSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        ResourceWeatherType $resource,
        WeatherTypeFactory $weatherTypeFactory,
        CollectionFactory $weatherTypeCollectionFactory,
        WeatherTypeSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->weatherTypeFactory = $weatherTypeFactory;
        $this->weatherTypeCollectionFactory = $weatherTypeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(\AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface $weatherType)
    {
        try {
            $this->resource->save($weatherType);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $weatherType;
    }

    public function getById(int $weatherTypeId)
    {
        $weatherType = $this->weatherTypeFactory->create();
        $this->resource->load($weatherType, $weatherTypeId, 'id');
        if (!$weatherType->getId()) {
            throw new NoSuchEntityException(__('The weathertype with the "%1" ID doesn\'t exist.', $weatherTypeId));
        }

        return $weatherType;
    }

    public function getByName(string $weatherTypeName)
    {
        $weatherType = $this->weatherTypeFactory->create();
        $this->resource->load($weatherType, $weatherTypeName, 'name');
        if (!$weatherType->getName()) {
            throw new NoSuchEntityException(__('The weathertype with the "%1" Name doesn\'t exist.', $weatherTypeName));
        }

        return $weatherType;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->weatherTypeCollectionFactory->create();
        $collection->load();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete(\AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface $weatherType)
    {
        try {
            $this->resource->delete($weatherType);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    public function deleteById(int $weatherTypeId)
    {
        return $this->delete($this->getById($weatherTypeId));
    }
}
