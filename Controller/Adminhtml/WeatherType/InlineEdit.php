<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Controller\Adminhtml\WeatherType;

use AdeoWeb\WeatherConditions\Api\Data\WeatherTypeInterface;
use AdeoWeb\WeatherConditions\Api\WeatherTypeRepositoryInterface;
use AdeoWeb\WeatherConditions\Model\WeatherType;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class InlineEdit extends Action
{
    public const ADMIN_RESOURCE = 'AdeoWeb_WeatherConditions::weathertype';

    private const PARAM_AJAX    = 'isAjax';
    private const PARAM_ITEM    = 'item';

    /**
     * @var WeatherTypeRepositoryInterface
     */
    private $weatherTypeRepository;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    public function __construct(
        Context $context,
        WeatherTypeRepositoryInterface $weatherTypeRepository,
        JsonFactory $jsonFactory
    ) {
        $this->weatherTypeRepository = $weatherTypeRepository;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    public function execute(): Json
    {
        $resultJson = $this->jsonFactory->create();
        $errorMessage = [['No error'], false];

        if (!$this->getRequest()->getParam(self::PARAM_AJAX)) {
            return $resultJson->setData([
                'messages' => $errorMessage[0],
                'error' => $errorMessage[1]
            ]);
        }

        $items = $this->getRequest()->getParam(self::PARAM_ITEM, []);
        if (!count($items)) {
            $errorMessage = [['Please correct the data sent.'], true];
        } else {
            $errorMessage = $this->saveInLineData($items);
        }

        return $resultJson->setData([
            'messages' => $errorMessage[0][0] ?? ['No error'],
            'error' => $errorMessage[1]
        ]);
    }

    private function getErrorWithWeatherTypeId(WeatherTypeInterface $weatherType, string $errorText): string
    {
        return '[WeatherType ID: ' . $weatherType->getId() . '] ' . $errorText;
    }

    private function saveInlineData(array $postItems): array
    {
        $errorMessage = [['No error'], false];
        foreach (array_keys($postItems) as $weatherTypeId) {
            /** @var WeatherType $weatherType */
            $weatherType = $this->weatherTypeRepository->getById($weatherTypeId);
            try {
                $weatherType->setData(array_merge($weatherType->getData(), $postItems[$weatherTypeId]));
                $this->weatherTypeRepository->save($weatherType);
            } catch (Exception $e) {
                $messages[] = $this->getErrorWithWeatherTypeId(
                    $weatherType,
                    (string)__($e->getMessage())
                );
                $errorMessage = [$messages, true];
            }
        }

        return $errorMessage;
    }
}
