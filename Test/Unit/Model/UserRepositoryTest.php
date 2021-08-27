<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherApplication\Test\Unit\Model;

use AdeoWeb\WeatherApplication\Model\User;
use AdeoWeb\WeatherApplication\Model\ResourceModel\User as ResourceUser;
use AdeoWeb\WeatherApplication\Model\UserFactory;
use AdeoWeb\WeatherApplication\Model\UserRepository;
use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private $messageManager;

    private $userFactory;

    private $user;

    private $resource;

    private $object;

    protected function setUp()
    {
        $this->messageManager = $this->getMockBuilder(ManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['addErrorMessage'])
            ->getMockForAbstractClass();

        $this->userFactory = $this->getMockBuilder( UserFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMockForAbstractClass();

        $this->resource = $this->getMockBuilder(ResourceUser::class)
            ->disableOriginalConstructor()
            ->setMethods(['load', 'save'])
            ->getMockForAbstractClass();

        $this->user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'setWeatherType'])
            ->getMockForAbstractClass();

        $this->object = new UserRepository(
            $this->messageManager,
            $this->resource,
            $this->userFactory
        );
    }

    protected function tearDown()
    {
        $this->messageManager = null;
        $this->resource = null;
        $this->userFactory = null;
        $this->object = null;
    }

    public function testGetByIdException()
    {
        $exception = new NoSuchEntityException();
        $this->userFactory->expects($this->once())->method('create')->willReturn($this->user);
        $this->resource->expects($this->once())->method('load');
        $this->user->expects($this->once())->method('getId')->willReturn(null)->willThrowException($exception);
        $this->assertNull($this->object->getById(1));
    }

    public function testGetById()
    {
        $this->userFactory->expects($this->once())->method('create')->willReturn($this->user);
        $this->resource->expects($this->once())->method('load');
        $this->user->expects($this->once())->method('getId')->willReturn(true);
        $this->assertInstanceOf(User::class, $this->object->getById(1));
    }

    public function testSaveByIdException()
    {
        $exception = new Exception();
        $this->user->expects($this->once())->willThrowException($exception);
        $this->messageManager->expects($this->once())->method('addErrorMessage');
        $this->assertFalse($this->object->saveById(1, 1));
    }

    public function testSaveById()
    {
        $this->user->expects($this->once())->method('setWeatherType');
        $this->resource->expects($this->once())->method('save');
        $this->assertTrue($this->object->saveById(1, 1));
    }
}
