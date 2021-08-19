<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\ViewModel;

use AdeoWeb\WeatherConditions\Model\WeatherTypeRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\FrameWork\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class WeatherTypeListingBanner extends Template implements ArgumentInterface
{
    private const PATH_MEDIA = 'pub/media/adeoweb/weatherconditions/';
    private const PATH_ADD_CATALOG_BANNER = 'catalog/frontend/banner_catalog';

    /**
     * @var WeatherTypeRepository
     */
    private $weatherTypeRepository;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        WeatherTypeRepository $weatherTypeRepository,
        array $data = []
    ) {
        $this->weatherTypeRepository = $weatherTypeRepository;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function getProductWeatherTypeImages(string $product): array
    {
        $answer = [];
        $arrays = explode(',', $product);
        foreach ($arrays as $id) {
            try {
                $weatherType = $this->weatherTypeRepository->getById((int)$id);
                array_push($answer, $weatherType->getIcon());
            } catch (NoSuchEntityException $e) {

            }
        }

        return $answer;
    }

    public function getImageUrl(string $icon): string
    {
        return rtrim($this->getUrl(self::PATH_MEDIA . $icon), '/');
    }

    public function isBannerActivated(?int $store = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::PATH_ADD_CATALOG_BANNER, ScopeInterface::SCOPE_STORE, $store);
    }
}
