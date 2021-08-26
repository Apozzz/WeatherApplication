<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Block;

use AdeoWeb\WeatherApplication\Api\Data\UserInterfaceFactory;
use AdeoWeb\WeatherApplication\Model\ResourceModel\WeatherType\CollectionFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\View\Element\Template;

class User extends Template
{
    private const PARAM_NAME                    = 'name';
    private const TEXT_NO_PREFERRED_WEATHERTYPE = 'No Preferred Weather Type';

    /**
     * @var UserInterfaceFactory
     */
    private $user;

    /**
     * @var CollectionFactory
     */
    private $weatherType;

    public function __construct(
        UserInterfaceFactory $user,
        CollectionFactory $weatherType,
        Template\Context $context,
        array $data = []
    ) {
        $this->user = $user;
        $this->weatherType = $weatherType;
        parent::__construct($context, $data);
    }

    /**
     * @return AbstractDb|AbstractCollection|null
     */
    public function getUsers()
    {
        return $this->user->create()->getCollection();
    }

    public function getWeatherTypeNameByID(?int $weatherTypeID): string
    {
        $weatherType = $this->weatherType->create()->getItemById($weatherTypeID);

        return $weatherType === null ? self::TEXT_NO_PREFERRED_WEATHERTYPE : $weatherType->getData(self::PARAM_NAME);
    }
}
