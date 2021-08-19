<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Block;

use AdeoWeb\WeatherConditions\Model\WeatherTypeRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\FrameWork\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class WeatherTypeBanner extends Template
{
    private const PATH_MEDIA = 'pub/media/adeoweb/weatherconditions/';
    private const PATH_ADD_PRODUCT_BANNER = 'catalog/frontend/banner_product';

    /**
     * @var Registry
     */
    private $registry;

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
        Registry $registry,
        ScopeConfigInterface $scopeConfig,
        WeatherTypeRepository $weatherTypeRepository,
        array $data = []
    ) {
        $this->weatherTypeRepository = $weatherTypeRepository;
        $this->scopeConfig = $scopeConfig;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    public function getProductWeatherTypeImages(): array
    {
        $answer = [];
        $arrays = explode(',', $this->getProduct()->getCustomAttribute('product_weathertype')->getValue());
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
        return $this->scopeConfig->isSetFlag(self::PATH_ADD_PRODUCT_BANNER, ScopeInterface::SCOPE_STORE, $store);
    }

    private function getProduct()
    {
        return $this->registry->registry('product');
    }
}
