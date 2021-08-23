<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Plugin;

use AdeoWeb\WeatherConditions\Model\WeatherTypeRepository;
use AdeoWeb\WeatherConditions\Block\WeatherTypeBanner;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Json\DecoderInterface;
use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable;
use Magento\Framework\Message\ManagerInterface;

class testPlug
{
    /**
     * @var WeatherTypeRepository
     */
    private $weatherTypeRepository;

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var DecoderInterface
     */
    protected $jsonDecoder;

    /**
     * @var WeatherTypeBanner
     */
    private $weatherTypeBanner;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        WeatherTypeRepository $weatherTypeRepository,
        EncoderInterface $jsonEncoder,
        DecoderInterface $jsonDecoder,
        WeatherTypeBanner $weatherTypeBanner,
        ProductRepository $productRepository,
        ManagerInterface $messageManager
    ) {
        $this->jsonDecoder = $jsonDecoder;
        $this->jsonEncoder = $jsonEncoder;
        $this->weatherTypeRepository = $weatherTypeRepository;
        $this->weatherTypeBanner = $weatherTypeBanner;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    public function afterGetJsonConfig(Configurable $subject, $config)
    {
        $configBannerArray = [];
        $config = $this->jsonDecoder->decode($config);
        foreach (array_keys($config['index']) as $productId) {
            $iconsArray = [];
            $productWeatherType = $this->getProductWeatherTypeAttributeById($productId);
            $weatherTypeIds = explode(',', $productWeatherType);
            foreach ($weatherTypeIds as $weatherTypeId) {
                array_push($iconsArray, $this->getProductWeatherTypeImage((int)$weatherTypeId));
            }
            $configBannerArray[$productId] = $iconsArray;
        }
        $config['banners'] = $configBannerArray;

        return $this->jsonEncoder->encode($config);
    }

    private function getProductWeatherTypeAttributeById(int $productId): string
    {
        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $product->getCustomAttribute('product_weathertype')->getValue();
    }

    private function getProductWeatherTypeImage(int $weatherTypeId): string
    {
        try {
            $weatherType = $this->weatherTypeRepository->getById($weatherTypeId);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->weatherTypeBanner->getImageUrl($weatherType->getIcon());
    }
}
