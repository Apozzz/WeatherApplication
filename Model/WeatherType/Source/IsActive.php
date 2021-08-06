<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model\WeatherType\Source;

use AdeoWeb\WeatherConditions\Model\WeatherType;
use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    /**
     * @var WeatherType
     */
    private $weatherType;

    public function __construct(WeatherType $weatherType)
    {
        $this->weatherType = $weatherType;
    }

    public function toOptionArray(): array
    {
        $availableOptions = $this->weatherType->getAvailableStatuses();
        $options = [];

        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }

        return $options;
    }
}
