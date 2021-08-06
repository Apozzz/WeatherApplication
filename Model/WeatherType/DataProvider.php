<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model\WeatherType;

use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface;
use AdeoWeb\WeatherConditions\Model\FileInfo;
use AdeoWeb\WeatherConditions\Model\ResourceModel\WeatherType\Collection;
use AdeoWeb\WeatherConditions\Model\ResourceModel\WeatherType\CollectionFactory;
use AdeoWeb\WeatherConditions\Model\WeatherType;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{
    private const FIELD_URL  = 'url';
    private const FIELD_SIZE = 'size';
    private const FIELD_TYPE = 'type';

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var array
     */
    private $loadedData;

    /**
     * @var FileInfo
     */
    private $fileInfo;

    public function __construct(
        CollectionFactory $weatherTypeCollectionFactory,
        PoolInterface $pool,
        FileInfo $fileInfo,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $weatherTypeCollectionFactory->create();
        $this->fileInfo = $fileInfo;
        $this->meta = $this->prepareMeta($this->meta);
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    public function prepareMeta(array $meta): array
    {
        return $meta;
    }

    public function getData(): ?array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        /** @var WeatherType $weatherType */
        foreach ($items as $weatherType) {
            $this->loadedData[$weatherType->getId()] = $weatherType->getData();

            if ($weatherType->getIcon()) {
                $icon = $this->convertValues($weatherType);
                $this->loadedData[$weatherType->getId()][WeatherTypeInterface::ICON] = $icon;
            }
        }

        if (!empty($data)) {
            $weatherType = $this->collection->getNewEmptyItem();
            $weatherType->setData($data);
            $this->loadedData[$weatherType->getId()] = $weatherType->getData();
        }

        return $this->loadedData;
    }

    private function convertValues($weatherType): array
    {
        $fileName = $weatherType->getIcon();
        $icon = [];
        if (!$this->fileInfo->isExist($fileName)) {
            return $icon;
        }

        $stat = $this->fileInfo->getStat($fileName);
        $mime = $this->fileInfo->getMimeType($fileName);
        $icon[0][WeatherTypeInterface::NAME] = $fileName;
        $icon[0][self::FIELD_URL] = $weatherType->getIconUrl();
        $icon[0][self::FIELD_SIZE] = isset($stat) ? $stat[self::FIELD_SIZE] : 0;
        $icon[0][self::FIELD_TYPE] = $mime;

        return $icon;
    }

    public function getMeta(): array
    {
        return parent::getMeta();
    }
}
