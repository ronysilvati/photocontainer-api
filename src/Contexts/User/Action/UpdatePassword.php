<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\PasswordUpdatedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\AtomicWorker;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class UpdatePassword
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var CryptoMethod
     */
    private $cryptoMethod;

    /**
     * @var AtomicWorker
     */
    private $atomicWorker;

    /**
     * UpdatePassword constructor.
     * @param UserRepository $userRepository
     * @param CryptoMethod $cryptoMethod
     * @param AtomicWorker $atomicWorker
     */
    public function __construct(
        UserRepository $userRepository,
        CryptoMethod $cryptoMethod,
        AtomicWorker $atomicWorker
    )
    {
        $this->userRepository = $userRepository;
        $this->cryptoMethod = $cryptoMethod;
        $this->atomicWorker = $atomicWorker;
    }

    /**
     * @param string $token
     * @param string $password
     * @return PasswordUpdatedResponse|DomainExceptionResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     * @throws DomainViolationException
     */
    public function handle(string $token, string $password)
    {
        if (empty($password)) {
            return new DomainExceptionResponse('A senha deve possuir um valor.');
        }

        $reqPwd = $this->userRepository->getValidToken($token);
        if(!$reqPwd) {
            throw new DomainViolationException('O seu pedido para troca de senha está inválido. Favor requisitar novamente.');
        }

        $user = $this->userRepository->findUser($reqPwd->getUserId());

        $user->changePwd($this->cryptoMethod->hash($password));

        $this->atomicWorker->execute(function() use ($user, $reqPwd) {
            $this->userRepository->updateUser($user);
            $this->userRepository->removePwdRequest($reqPwd);
        });

        $this->userRepository->updateUser($user);
        return new PasswordUpdatedResponse();
    }
}