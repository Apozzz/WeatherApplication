<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Controller\Adminhtml\WeatherType;

use AdeoWeb\WeatherConditions\Api\WeatherTypeRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;

class Delete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'AdeoWeb_WeatherConditions::delete';

    private const PARAM_ID      = 'id';

    /**
     * @var WeatherTypeRepositoryInterface
     */
    private $weatherTypeRepository;

    public function __construct(
        Action\Context $context,
        WeatherTypeRepositoryInterface $weatherTypeRepository
    ) {
        $this->weatherTypeRepository = $weatherTypeRepository;
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        $id = (int)$this->getRequest()->getParam(self::PARAM_ID);
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$id) {
            $this->messageManager->addErrorMessage(__('We can\'t find a weathertype to delete.'));

            return $resultRedirect->setPath('*/*/');
        }

        try {
            $this->weatherTypeRepository->deleteById($id);
            $this->messageManager->addSuccessMessage(__('The weathertype has been deleted.'));

            return $resultRedirect->setPath('*/*/');
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            return $resultRedirect->setPath('*/*/edit', [self::PARAM_ID => $id]);
        }
    }
}
