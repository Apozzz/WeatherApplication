<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Block\Adminhtml\WeatherType\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        if (!$this->getWeatherTypeId()) {
            return [];
        }

        return [
            'label' => __('Delete WeatherType'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})',
            'sort_order' => 20,
        ];
    }

    public function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['id' => $this->getWeatherTypeId()]);
    }
}
