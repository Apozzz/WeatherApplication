<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Block;

use Magento\Framework\View\Element\Template;

class WeatherInfo extends Template
{
    private const PARAM_TYPE = 'type';
    private const RESULT_DEFAULT = 'Sun';

    public function getWeatherType(): string
    {
        return $this->getRequest()->getParam(self::PARAM_TYPE) ?? self::RESULT_DEFAULT;
    }
}
