<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Controller\Adminhtml\WeatherType;

use AdeoWeb\WeatherConditions\Api\WeatherTypeRepositoryInterface;
use AdeoWeb\WeatherConditions\Controller\Adminhtml\WeatherType;
use AdeoWeb\WeatherConditions\Model\WeatherTypeFactory;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Edit extends WeatherType implements HttpGetActionInterface
{
    private const PARAM_ID = 'id';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var WeatherTypeFactory
     */
    private $weatherTypeFactory;

    /**
     * @var WeatherTypeRepositoryInterface
     */
    private $weatherTypeRepository;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        WeatherTypeFactory $weatherTypeFactory,
        WeatherTypeRepositoryInterface $weatherTypeRepository
    ) {
        $this->weatherTypeRepository = $weatherTypeRepository;
        $this->weatherTypeFactory = $weatherTypeFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return Page|Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(self::PARAM_ID);
        try {
            if (!is_null($id)) {
                settype($id, 'int');
                $weatherType = $this->weatherTypeRepository->getById($id);
            } else {
                $id = null;
                $weatherType = $this->weatherTypeFactory->create();

                return $this->initResultPage($id, $weatherType);
            }
        } catch (NoSuchEntityException $e) {
            return $this->getResultRedirect($e);
        }

        return $this->initResultPage($id, $weatherType);
    }

    private function initResultPage(?int $id, $weatherType): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit WeatherType') : __('New WeatherType'),
            $id ? __('Edit WeatherType') : __('New WeatherType')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('WeatherTypes'));
        $resultPage->getConfig()->getTitle()->prepend($weatherType->getId() ? $weatherType->getName() : __('New WeatherType'));

        return $resultPage;
    }

    private function getResultRedirect(?Exception $error): Redirect
    {
        if ($error) {
            $this->messageManager->addErrorMessage($error->getMessage());
        } else {
            $this->messageManager->addErrorMessage(__('This weathertype no longer exists.'));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
