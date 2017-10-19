<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Application\Resources\Emails\PasswordRequestEmail;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\RequestPassword;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\RequestPasswordCreated;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\TokenGeneratorHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\AtomicWorker;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class RequestPwdChange
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var TokenGeneratorHelper
     */
    private $tokenGeneratorHelper;

    /**
     * @var EmailHelper
     */
    private $emailHelper;

    /**
     * @var AtomicWorker
     */
    private $atomicWorker;

    /**
     * RequestPwdChange constructor.
     * @param UserRepository $userRepository
     * @param TokenGeneratorHelper $tokenGeneratorHelper
     * @param EmailHelper $emailHelper
     * @param AtomicWorker $atomicWorker
     */
    public function __construct(
        UserRepository $userRepository,
        TokenGeneratorHelper $tokenGeneratorHelper,
        EmailHelper $emailHelper,
        AtomicWorker $atomicWorker
    )
    {
        $this->userRepository = $userRepository;
        $this->tokenGeneratorHelper = $tokenGeneratorHelper;
        $this->emailHelper = $emailHelper;
        $this->atomicWorker = $atomicWorker;
    }

    /**
     * @param string $email
     * @return RequestPasswordCreated|DomainExceptionResponse
     * @throws \RuntimeException
     */
    public function handle(string $email)
    {
        $user = $this->userRepository->findUser(null, $email);
        if(!$user) {
            return new DomainExceptionResponse('O email não foi encontrado na base de usuários.');
        }

        $pwdReq = $this->userRepository->findPwdRequest($user);

        if ($pwdReq) {
            if (!$pwdReq->isActive()) {
                $this->userRepository->removePwdRequest($pwdReq);
            } else {
                $this->sendEmail($user, $pwdReq);
                return new RequestPasswordCreated($pwdReq);
            }
        }

        $pwdReq = $this->atomicWorker->execute(function() use ($user) {
            $reqPwd = new RequestPassword(null, $this->tokenGeneratorHelper->generate(), $user->getId());
            $this->userRepository->createPwdRequest($reqPwd);

            $this->sendEmail($user, $reqPwd);
            return $reqPwd;
        }, function(\Exception $e) {
            throw new \RuntimeException('Falha no pedido de troca de senha.');
        });

        return new RequestPasswordCreated($pwdReq);
    }

    /**
     * @param User $user
     * @param RequestPassword $reqPwd
     */
    private function sendEmail(User $user, RequestPassword $reqPwd): void
    {
        $data = [
            '{NAME}' => $user->getName(),
            '{TOKEN}' => getenv('SITE_DOMAIN').'/password-update?token='.$reqPwd->getToken(),
            '{VALID_UNTIL}' => $reqPwd->getValidUntil()->format('d/m/Y H:i'),
            '{CREATION_DATE}' => date('d/m/y H:i:s'),
        ];

        $email = new PasswordRequestEmail(
            $data,
            ['name' => $user->getName(), 'email' => $user->getEmail()]
        );

        $this->emailHelper->send($email);
    }
}