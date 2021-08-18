<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model;

use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface;
use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeSearchResultsInterfaceFactory;
use AdeoWeb\WeatherConditions\Api\WeatherTypeRepositoryInterface;
use AdeoWeb\WeatherConditions\Model\ResourceModel\WeatherType as ResourceWeatherType;
use AdeoWeb\WeatherConditions\Model\ResourceModel\WeatherType\CollectionFactory;
use Exception;
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
     * @var \AdeoWeb\WeatherConditions\Model\CollectionProcessor
     */
    private $collectionProcessor;

    public function __construct(
        ResourceWeatherType $resource,
        WeatherTypeFactory $weatherTypeFactory,
        CollectionFactory $weatherTypeCollectionFactory,
        CollectionProcessor $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->weatherTypeFactory = $weatherTypeFactory;
        $this->weatherTypeCollectionFactory = $weatherTypeCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(WeatherTypeInterface $weatherType)
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

        $this->collectionProcessor->addFiltersToCollection($searchCriteria, $collection);
        $this->collectionProcessor->addSortOrdersToCollection($searchCriteria, $collection);
        $this->collectionProcessor->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->collectionProcessor->buildSearchResult($searchCriteria, $collection);
    }

    public function delete(WeatherTypeInterface $weatherType)
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
        try {
            $this->delete($this->getById($weatherTypeId));
        } catch (Exception $exception) {
            throw new Exception(__($exception->getMessage()));
        }

        return true;
    }
}
