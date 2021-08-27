<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model;

use AdeoWeb\WeatherApplication\Api\WeatherTypeRepositoryInterface;
use AdeoWeb\WeatherApplication\Model\ResourceModel\WeatherType as ResourceWeatherType;
use Magento\Framework\Exception\NoSuchEntityException;

class WeatherTypeRepository implements WeatherTypeRepositoryInterface
{
    private const FIELD_ID = 'weathertype_id';

    /**
     * @var WeatherTypeFactory
     */
    private $weatherTypeFactory;

    /**
     * @var ResourceWeatherType
     */
    private $resource;

    public function __construct(
        ResourceWeatherType$resource,
        WeatherTypeFactory $weatherTypeFactory
    ) {
        $this->resource = $resource;
        $this->weatherTypeFactory = $weatherTypeFactory;
    }

    public function getById(int $weatherTypeId)
    {
        $weatherType = $this->weatherTypeFactory->create();
        $this->resource->load($weatherType, $weatherTypeId, self::FIELD_ID);
        if (!$weatherType->getId()) {
            throw new NoSuchEntityException(__('Weather Type with the "%1" ID doesn\'t exist.', $weatherTypeId));
        }

        return $weatherType;
    }
}
