<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Block;

use AdeoWeb\WeatherApplication\Model\WeatherTypeFactory;
use Magento\Framework\View\Element\Template;

class Api extends Template
{
    /**
     * @var WeatherTypeFactory
     */
    private $weatherType;

    public function __construct(
        WeatherTypeFactory $weatherType,
        Template\Context $context,
        array $data = []
    ) {
        $this->weatherType= $weatherType;
        parent::__construct($context, $data);
    }

    public function getWeatherTypes(): array
    {
        $weatherTypes = [];
        $collection = $this->weatherType->create()->getCollection();
        foreach ($collection as $weather) {
            $weatherTypes[$weather->getName()] = $weather->getId();
        }

        return $weatherTypes;
    }
}
