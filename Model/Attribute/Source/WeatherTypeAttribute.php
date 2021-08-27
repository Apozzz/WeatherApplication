<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model\Attribute\Source;

use AdeoWeb\WeatherApplication\Model\ResourceModel\WeatherType\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class WeatherTypeAttribute extends AbstractSource
{
    private const PARAM_VALUE = 'value';
    private const PARAM_LABEL = 'label';

    /**
     * @var CollectionFactory
     */
    private $weatherTypeCollectionFactory;

    public function __construct(
        CollectionFactory $weatherTypeCollectionFactory
    ) {
        $this->weatherTypeCollectionFactory = $weatherTypeCollectionFactory;
    }

    public function getAllOptions(): array
    {
        $collection = $this->weatherTypeCollectionFactory->create()->load();
        $options = [];
        $format = '(ID: %d) %s';

        foreach ($collection as $weatherType) {
            $options[] = [
                self::PARAM_VALUE => $weatherType->getId(),
                self::PARAM_LABEL => sprintf($format, $weatherType->getId(), $weatherType->getName())
            ];
        }

        return $options;
    }
}
