<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Block;

use AdeoWeb\WeatherApplication\Model\ResourceModel\WeatherType\CollectionFactory;
use Magento\Framework\View\Element\Template;

class Api extends Template
{
    /**
     * @var CollectionFactory
     */
    private $weatherTypeCollectionFactory;

    public function __construct(
        CollectionFactory $weatherTypeCollectionFactory,
        Template\Context $context,
        array $data = []
    ) {
        $this->weatherTypeCollectionFactory = $weatherTypeCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getWeatherTypes(): array
    {
        $weatherTypes = [];
        $collection = $this->weatherTypeCollectionFactory->create()->load();
        foreach ($collection as $weather) {
            $weatherTypes[$weather->getId()] = $weather->getName();
        }

        return $weatherTypes;
    }
}
