<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Email\NewUserEmail;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserCreatedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventGeneratorTrait;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class CreateUser
{
    use EventGeneratorTrait;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var CryptoMethod
     */
    protected $cryptoMethod;

    /**
     * CreateUser constructor.
     * @param UserRepository $userRepository
     * @param CryptoMethod $cryptoMethod
     */
    public function __construct(UserRepository $userRepository, CryptoMethod $cryptoMethod)
    {
        $this->userRepository = $userRepository;
        $this->cryptoMethod = $cryptoMethod;
    }

    /**
     * @param User $user
     * @return UserCreatedResponse|DomainExceptionResponse
     */
    public function handle(User $user)
    {
        try {
            $encrypted = empty($user->getPwd()) ? '' : $this->cryptoMethod->hash($user->getPwd());
            $user = $this->userRepository->createUser($user, $encrypted);

            $this->sendEmail($user);

            return new UserCreatedResponse($user);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }

    /**
     * @param User $user
     */
    private function sendEmail(User $user)
    {
        $data = [
            '{NAME}' => $user->getName(),
            '{EMAIL}' => $user->getEmail(),
            '{PROFILE}' => $user->getProfile()->getProfileId() === Profile::PHOTOGRAPHER ? 'Fotografo' : 'Publisher',
        ];

        $email = new NewUserEmail(
            $data,
            ['name' => getenv('ADMIN_EMAIL_NAME'), 'email' => getenv('ADMIN_EMAIL')]
        );

        $this->addEvent('generic.sendmail', $email);
    }
}
