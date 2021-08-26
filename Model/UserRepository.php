<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Model;

use AdeoWeb\WeatherApplication\Api\UserRepositoryInterface;
use AdeoWeb\WeatherApplication\Model\ResourceModel\User as ResourceUser;
use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class UserRepository implements UserRepositoryInterface
{
    private const FIELD_ID = 'user_id';

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var ResourceUser
     */
    private $resource;

    public function __construct(
        ManagerInterface $messageManager,
        ResourceUser $resource,
        UserFactory $userFactory
    ) {
        $this->messageManager = $messageManager;
        $this->resource = $resource;
        $this->userFactory = $userFactory;
    }

    public function getById(int $userId)
    {
        $user = $this->userFactory->create();
        $this->resource->load($user, $userId, self::FIELD_ID);
        if (!$user->getId()) {
            throw new NoSuchEntityException(__('The weathertype with the "%1" ID doesn\'t exist.', $userId));
        }

        return $user;
    }

    public function saveById(int $userId, int $weatherTypeId)
    {
        try {
            $user = $this->getById($userId);
            $user->setWeatherType($weatherTypeId);
            $this->resource->save($user);
        } catch (NoSuchEntityException|Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            return false;
        }

        return true;
    }
}
