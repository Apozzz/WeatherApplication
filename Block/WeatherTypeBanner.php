<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use AdeoWeb\WeatherConditions\Model\WeatherTypeRepository;

class WeatherTypeBanner extends Template
{
    private const PATH_MEDIA = 'pub/media/adeoweb/weatherconditions/';
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var WeatherTypeRepository
     */
    private $weatherTypeRepository;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        WeatherTypeRepository $weatherTypeRepository,
        array $data = []
    ) {
        $this->weatherTypeRepository = $weatherTypeRepository;
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

    private function getProduct()
    {
        return $this->registry->registry('product');
    }
}
