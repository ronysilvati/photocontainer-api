<?php

namespace Tests\Contexts\User;

use Codeception\Util\Stub;
use Helper\TestAtomicWorker;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnUserCreated;
use PhotoContainer\PhotoContainer\Contexts\User\Action\CreateUser;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Event\PublisherCreated;
use PhotoContainer\PhotoContainer\Contexts\User\Event\UserCreated;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserCreatedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventRecorder;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\AtomicWorker;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimPHPDI;
use Psr\Container\ContainerInterface;

class UserContextTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var CreateUser
     */
    private $createUser;

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $phpDI = new SlimPHPDI();
        $this->container = $phpDI->getContainer();

        $this->repository = Stub::makeEmpty(
            UserRepository::class,
            [
                'isUserUnique' => true,
                'createUser' => function(User $user, $hash) {
                    $user->changeId(1);
                    $user->getDetails()->changeId(1);
                    return $user;
                }
            ]
        );

        parent::__construct($name, $data, $dataName);
    }

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreatePhotographer()
    {
        $this->createUser = new CreateUser(
            $this->repository,
            $this->container->get(CryptoMethod::class),
            new TestAtomicWorker()
        );

        $profile = new Profile(null, null, Profile::PHOTOGRAPHER);

        $response = $this->createUser->handle(
            new User(null, 'Teste', 'teste@teste.com', '1234', new Details(), $profile)
        );

        $events = EventRecorder::getInstance()->pullEvents();
        $event = $events[0];


        $this->assertInstanceOf(UserCreatedResponse::class, $response);
        $this->assertArrayHasKey('details', $response->jsonSerialize());

        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserCreated::class, $event);
        $this->assertInstanceOf(User::class, $event->getUser());
        $this->assertEquals('user_created', $event->getName());
        $this->assertEquals(1, $event->getUser()->getId());
    }

    public function testCreatePublisher()
    {
        $this->createUser = new CreateUser(
            $this->repository,
            $this->container->get(CryptoMethod::class),
            new TestAtomicWorker()
        );

        $details = new Details(null, 'http://blog.com');
        $profile = new Profile(null, null, Profile::PUBLISHER);

        $response = $this->createUser->handle(
            new User(null, 'Teste', 'teste@teste.com', '1234', $details, $profile)
        );

        $events = EventRecorder::getInstance()->pullEvents();

        $this->assertInstanceOf(UserCreatedResponse::class, $response);
        $this->assertArrayHasKey('details', $response->jsonSerialize());

        $this->assertCount(2, $events);

        $this->assertInstanceOf(UserCreated::class, $events[0]);
        $this->assertInstanceOf(User::class, $events[0]->getUser());
        $this->assertEquals('user_created', $events[0]->getName());
        $this->assertEquals(1, $events[0]->getUser()->getId());

        $this->assertInstanceOf(PublisherCreated::class, $events[1]);
        $this->assertInstanceOf(User::class, $events[1]->getUser());
        $this->assertEquals('publisher_registered', $events[1]->getName());
        $this->assertEquals(1, $events[1]->getUser()->getId());
    }
}