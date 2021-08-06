<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Ui\DataProvider\WeatherType\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    protected function _initSelect()
    {
        $this->addFilterToMap('id', 'main_table.id');
        $this->addFilterToMap('name', 'main_table.name');
        $this->addFilterToMap('alias', 'main_table.alias');
        $this->addFilterToMap('icon', 'main_table.icon');
        $this->addFilterToMap('description', 'main_table.description');
        parent::_initSelect();
    }
}
