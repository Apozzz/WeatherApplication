<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;

abstract class WeatherType extends Action
{
    public const ADMIN_RESOURCE = 'AdeoWeb_WeatherConditions::weathertype';

    protected function initPage(Page $resultPage): Page
    {
        $resultPage->setActiveMenu('AdeoWeb_WeatherConditions::weatherconditions_weathertype')
            ->addBreadcrumb(__('WeatherConditions'), __('WeatherConditions'))
            ->addBreadcrumb(__('Static WeatherTypes'), __('Static WeatherTypes'));

        return $resultPage;
    }
}
