<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Ui\Component\WeatherType\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Escaper;

class Actions extends Column
{
    private const URL_PATH_EDIT   = 'weatherconditions/weathertype/edit';
    private const URL_PATH_DELETE = 'weatherconditions/weathertype/delete';

    private const PARAM_ID        = 'id';
    private const PARAM_NAME      = 'name';
    private const PARAM_ITEMS     = 'items';
    private const PARAM_DATA      = 'data';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Escaper
     */
    private $escaper;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        Escaper $escaper,
        array $components = [],
        array $data = []
    ) {
        $this->escaper = $escaper;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource[self::PARAM_DATA][self::PARAM_ITEMS])) {
            return $dataSource;
        }
        foreach ($dataSource[self::PARAM_DATA][self::PARAM_ITEMS] as & $item) {
            if (!isset($item[self::PARAM_ID])) {
                continue;
            }

            $title = $this->escaper->escapeHtmlAttr($item[self::PARAM_NAME]);
            $item[$this->getData(self::PARAM_NAME)] = [
                'edit' => $this->getEditButtonData($item),
                'delete' => $this->getDeleteButtonData($item, $title)
            ];
        }

        return $dataSource;
    }

    private function getDeleteButtonData(array $item, string $title): array
    {
        return [
            'href' => $this->urlBuilder->getUrl(
                static::URL_PATH_DELETE,
                [
                    'id' => $item[self::PARAM_ID],
                ]
            ),
            'label' => __('Delete'),
            'confirm' => [
                'title' => __('Delete %1', $title),
                'message' => __('Are you sure you want to delete a %1 record?', $title),
            ],
            'post' => true,
        ];
    }

    private function getEditButtonData(array $item): array
    {
        return [
            'href' => $this->urlBuilder->getUrl(
                static::URL_PATH_EDIT,
                [
                    'id' => $item[self::PARAM_ID],
                ]
            ),
            'label' => __('Edit'),
        ];
    }
}
