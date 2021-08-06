<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Controller\Adminhtml\WeatherType;

use AdeoWeb\WeatherConditions\Controller\Adminhtml\WeatherType;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;

class NewAction extends WeatherType implements HttpGetActionInterface
{
    private const FORWARD_EDIT = 'edit';

    /**
     * @var ForwardFactory
     */
    private $resultForwardFactory;

    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $resultForward = $this->resultForwardFactory->create();

        return $resultForward->forward(self::FORWARD_EDIT);
    }
}
