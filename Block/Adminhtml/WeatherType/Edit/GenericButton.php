<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Block\Adminhtml\WeatherType\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class GenericButton
{
    private const PARAM_ID = 'id';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var UrlInterface
     */
    private $url;

    public function __construct(
        RequestInterface $request,
        UrlInterface $url
    ) {
        $this->request = $request;
        $this->url = $url;
    }

    public function getWeatherTypeId(): int
    {
        return (int)$this->request->getParam(self::PARAM_ID);
    }

    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->url->getUrl($route, $params);
    }
}
