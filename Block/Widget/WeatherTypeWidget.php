<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Block\Widget;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\Widget\NewWidget;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Widget\Block\BlockInterface;

class WeatherTypeWidget extends NewWidget implements BlockInterface
{
    protected $_template = "widget/weathertype_widget.phtml";

    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        HttpContext $httpContext,
        Json $serializer = null,
        array $data = [])
    {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $httpContext, $data, $serializer);
    }


    public function getProductsWithTomorrowsWeatherOnly(): array
    {
        $productsWithTomorrowsWeather = [];

        foreach ($this->getProducts() as $product) {
            $productWeatherTypeIds = $product->getCustomAttribute('product_weathertype')->getValue();
            if (preg_match('/(' . $this->getTomorrowsWeather() . ')/', $productWeatherTypeIds)) {
                array_push($productsWithTomorrowsWeather, $product);
            }
        }

        return $productsWithTomorrowsWeather;
    }

    private function getProducts(): Collection
    {
        return $this->productCollectionFactory->create()->addAttributeToSelect('*');
    }

    private function getTomorrowsWeather(): string
    {
        return $this->getData('weathertype_tomorrows_weather');
    }
}
