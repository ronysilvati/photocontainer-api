<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Action;

use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\Auth;
use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\AuthRepository;
use PhotoContainer\PhotoContainer\Contexts\Auth\Response\AuthenticatedResponse;
use PhotoContainer\PhotoContainer\Contexts\Auth\Response\NotPermittedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod;

class AuthenticateUser
{
    protected $repository;
    protected $cryptoMethod;

    public function __construct(AuthRepository $repository, CryptoMethod $cryptoMethod)
    {
        $this->repository = $repository;
        $this->cryptoMethod = $cryptoMethod;
    }

    public function handle(Auth $auth, CryptoMethod $tokenGenerator)
    {
        try {
            $user = $this->repository->find($auth->getUser());

            if ($this->cryptoMethod->verify($auth->getPassword(), $user->password) === false) {
                throw new \Exception("A senha está incorreta");
            }

            $token = array(
//                "iss" => "http://example.org",
//                "aud" => "http://example.com",
                "iat" => 1356999524,
                "nbf" => 1357000000
            );
            $token = $tokenGenerator->hash($token);

            return new AuthenticatedResponse($token);
        } catch (\Exception $e) {
            return new NotPermittedResponse($e->getMessage());
        }
    }
}
