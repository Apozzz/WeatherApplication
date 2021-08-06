<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Controller\Adminhtml\WeatherType;

use AdeoWeb\WeatherConditions\Api\WeatherTypeRepositoryInterface;
use AdeoWeb\WeatherConditions\Controller\Adminhtml\WeatherType as WeatherTypeAbstract;
use AdeoWeb\WeatherConditions\Model\ImageUploader;
use AdeoWeb\WeatherConditions\Model\WeatherTypeFactory;
use AdeoWeb\WeatherConditions\Model\WeatherType;
use Exception;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends WeatherTypeAbstract implements HttpPostActionInterface
{
    private const PARAM_STATUS = 'status';
    private const PARAM_ID = 'id';
    private const PARAM_ICON = 'icon';
    private const PARAM_NAME = 'name';

    /**
     * @var WeatherTypeFactory
     */
    private $weatherTypeFactory;

    /**
     * @var WeatherTypeRepositoryInterface
     */
    private $weatherTypeRepository;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    public function __construct(
        Context $context,
        WeatherTypeFactory $weatherTypeFactory,
        WeatherTypeRepositoryInterface $weatherTypeRepository,
        ImageUploader $imageUploader
    ) {
        $this->weatherTypeFactory = $weatherTypeFactory;
        $this->weatherTypeRepository = $weatherTypeRepository;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $imageName = '';
        if (!empty($data[self::PARAM_ICON])) {
            $imageName = $data[self::PARAM_ICON][0][self::PARAM_NAME];
        }

        $data = $this->setDataParameters($data, $imageName);
        $weatherType = $this->weatherTypeFactory->create();
        $id = (int)$this->getRequest()->getParam(self::PARAM_ID);
        if (!($this->setModelData($id, $weatherType, $data))) {
            return $resultRedirect->setPath('*/*/');
        }
        if ($this->saveWeatherType($weatherType, $imageName)) {
            return $resultRedirect->setPath('*/*/edit', [self::PARAM_ID => $weatherType->getId()]);
        }

        return $resultRedirect->setPath('*/*/edit', [self::PARAM_ID => $id]);
    }

    private function saveWeatherType(WeatherType $weatherType, string $imageName): bool
    {
        try {
            $this->weatherTypeRepository->save($weatherType);
            if ($imageName) {
                $this->imageUploader->moveFileFromTmp($imageName);
            }
            $this->messageManager->addSuccessMessage(__('You saved the WeatherType.'));

            return true;
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the weatherType.'));
        }

        return false;
    }

    private function setDataParameters(array $data, string $imageName): array
    {
        if (isset($data[self::PARAM_STATUS]) && $data[self::PARAM_STATUS] === '1') {
            $data[self::PARAM_STATUS] = WeatherType::STATUS_ENABLED;
            $data[self::PARAM_ICON] = $imageName;
        }
        if (empty($data[self::PARAM_ID])) {
            $data[self::PARAM_ID] = null;
        }

        return $data;
    }

    private function setModelData(?int $id, &$weatherType, array $data): bool
    {
        if (!$id) {
            $weatherType->setData($data);

            return true;
        }

        try {
            $weatherType = $this->weatherTypeRepository->getById($id);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('This WeatherType no longer exists.'));

            return false;
        }

        $weatherType->setData($data);

        return true;
    }
}
