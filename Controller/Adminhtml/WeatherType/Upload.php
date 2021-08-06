<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Controller\Adminhtml\WeatherType;

use AdeoWeb\WeatherConditions\Model\ImageUploader;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Upload extends Action
{
    private const PARAM_ICON = 'icon';
    private const PARAM_KEY  = 'param_name';

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $imageId = $this->getRequest()->getParam(self::PARAM_KEY, self::PARAM_ICON);
        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
