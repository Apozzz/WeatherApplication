<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Block;

use AdeoWeb\WeatherApplication\Api\WeatherTypeRepositoryInterface;
use AdeoWeb\WeatherApplication\Model\ResourceModel\User\Collection;
use AdeoWeb\WeatherApplication\Model\ResourceModel\User\CollectionFactory;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template;

class User extends Template
{
    private const TEXT_NO_PREFERRED_WEATHERTYPE = 'No Preferred Weather Type';

    /**
     * @var WeatherTypeRepositoryInterface
     */
    private $weatherTypeRepository;

    /**
     * @var CollectionFactory
     */
    private $userCollectionFactory;

    public function __construct(
        CollectionFactory $userCollectionFactory,
        WeatherTypeRepositoryInterface $weatherTypeRepository,
        Template\Context $context,
        array $data = []
    ) {
        $this->userCollectionFactory = $userCollectionFactory;
        $this->weatherTypeRepository = $weatherTypeRepository;
        parent::__construct($context, $data);
    }

    public function getUsers(): Collection
    {
        return $this->userCollectionFactory->create()->load();
    }

    /**
     * @param int|null $weatherTypeID
     * @return Phrase|string
     */
    public function getWeatherTypeNameByID(?int $weatherTypeID)
    {
        if (!$weatherTypeID) {
            return __(self::TEXT_NO_PREFERRED_WEATHERTYPE);
        }

        return $this->weatherTypeRepository->getById($weatherTypeID)->getName();
    }
}
