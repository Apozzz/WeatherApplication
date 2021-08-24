<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model\Attribute\Source;

use AdeoWeb\WeatherApplication\Api\Data\WeatherTypeInterfaceFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class WeatherTypeAttribute extends AbstractSource
{
    /**
     * @var WeatherTypeInterfaceFactory
     */
    private $weatherType;

    public function __construct(
        WeatherTypeInterfaceFactory $weatherType
    ) {
        $this->weatherType = $weatherType;
    }

    public function getAllOptions(): array
    {
        $collection = $this->weatherType->create()->getCollection();
        $options = [];

        foreach ($collection as $weatherType) {
            $options[] = [
                'value' => $weatherType->getId(),
                'label' => '(ID:' . $weatherType->getId() . ')   ' . $weatherType->getName()
            ];
        }

        return $options;
    }
}
