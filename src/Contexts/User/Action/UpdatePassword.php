<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Command\UpdatePasswordCommand;
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
     * @param UpdatePasswordCommand $command
     * @return PasswordUpdatedResponse|DomainExceptionResponse
     * @throws DomainViolationException
     */
    public function handle(UpdatePasswordCommand $command)
    {
        if (empty($command->getPwd())) {
            return new DomainExceptionResponse('A senha deve possuir um valor.');
        }

        $reqPwd = $this->userRepository->getValidToken($command->getToken());
        if(!$reqPwd) {
            throw new DomainViolationException(
                'Seu pedido para troca de senha está inválido. Favor requisitar novamente.'
            );
        }

        $user = $this->userRepository->findUser($reqPwd->getUserId());
        if (!$user) {
            throw new DomainViolationException('Usuário não encontrado.');
        }

        $user->changePwd($this->cryptoMethod->hash($command->getPwd()));

        $this->atomicWorker->execute(function() use ($user, $reqPwd) {
            $this->userRepository->updateUser($user);
            $this->userRepository->removePwdRequest($reqPwd);
        });

        $this->userRepository->updateUser($user);
        return new PasswordUpdatedResponse();
    }
}