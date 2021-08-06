<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Ui\Component\WeatherType\Listing\Column;

use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface;
use AdeoWeb\WeatherConditions\Model\WeatherType;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Thumbnail extends Column
{
    private const URL_PATH_EDIT     = 'weatherconditions/weathertype/edit';

    private const DATASOURCE_DATA   = 'data';
    private const DATASOURCE_ITEMS  = 'items';
    private const FIELDNAME_SRC     = '_src';
    private const FIELDNAME_ORIGSRC = '_orig_src';
    private const FIELDNAME_LINK    = '_link';
    private const FIELDNAME_ALT     = '_alt';
    private const PARAM_NAME        = 'name';

    /**
     * @var WeatherType
     */
    private $weatherType;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        WeatherType $weatherType,
        UrlInterface $urlBuilder,
        ManagerInterface $messageManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->messageManager = $messageManager;
        $this->weatherType = $weatherType;
        $this->urlBuilder = $urlBuilder;
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource[self::DATASOURCE_DATA][self::DATASOURCE_ITEMS])) {
            return $dataSource;
        }

        $fieldName = $this->getData(self::PARAM_NAME);
        foreach ($dataSource[self::DATASOURCE_DATA][self::DATASOURCE_ITEMS] as & $item) {
            $weatherType = new DataObject($item);

            try {
                $item[$fieldName . self::FIELDNAME_SRC] = $this->weatherType->getIconUrl($weatherType[WeatherTypeInterface::ICON]);
                $item[$fieldName . self::FIELDNAME_ORIGSRC] = $this->weatherType->getIconUrl($weatherType[WeatherTypeInterface::ICON]);
            } catch(LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            $item[$fieldName . self::FIELDNAME_LINK] = $this->urlBuilder->getUrl(
                self::URL_PATH_EDIT,
                ['id' => $weatherType[WeatherTypeInterface::ID]]
            );
            $item[$fieldName . self::FIELDNAME_ALT] = $weatherType[WeatherTypeInterface::NAME];
        }

        return $dataSource;
    }
}
