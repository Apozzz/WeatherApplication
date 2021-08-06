<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model;

use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface;
use Exception;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class WeatherType extends AbstractModel implements WeatherTypeInterface
{
    public const STATUS_ENABLED  = 1;
    public const STATUS_DISABLED = 0;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        ManagerInterface $messageManager,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->messageManager = $messageManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\WeatherType::class);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function getAlias()
    {
        return $this->getData(self::ALIAS);
    }

    public function getIcon()
    {
        return $this->getData(self::ICON);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * @param int $id
     * @return WeatherTypeInterface|WeatherType
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function setStatus(int $status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function setName(string $name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function setAlias(string $alias)
    {
        return $this->setData(self::ALIAS, $alias);
    }

    public function setIcon(string $icon)
    {
        return $this->setData(self::ICON, $icon);
    }

    public function setDescription(string $description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function setCreationTime(string $creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    public function setUpdateTime(string $updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    public function getIconUrl($iconName = null): string
    {
        $url = '';
        $format = '%s/%s';
        if (!$iconName) {
            $iconName = $this->getData(self::ICON);
        }
        if ($iconName) {
            try {
                $url = $this->storeManager->getStore()->getBaseUrl(
                        UrlInterface::URL_TYPE_MEDIA
                    ) . sprintf($format, FileInfo::ENTITY_MEDIA_PATH, $iconName);
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        return $url;
    }
}
