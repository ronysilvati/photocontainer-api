<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Action;

use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\Auth;
use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\AuthRepository;
use PhotoContainer\PhotoContainer\Contexts\Auth\Response\AuthenticatedResponse;
use PhotoContainer\PhotoContainer\Contexts\Auth\Response\NotPermittedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\JwtGenerator;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticateUser
{
    /**
     * @var AuthRepository
     */
    protected $repository;

    /**
     * @var CryptoMethod
     */
    protected $cryptoMethod;

    /**
     * @var JwtGenerator
     */
    protected $jwtGenerator;

    public function __construct(AuthRepository $repository, CryptoMethod $cryptoMethod, JwtGenerator $jwtGenerator)
    {
        $this->repository = $repository;
        $this->cryptoMethod = $cryptoMethod;
        $this->jwtGenerator = $jwtGenerator;
    }

    public function handle(ServerRequestInterface $request)
    {
        try {
            $data = $request->getParsedBody();
            $auth = new Auth($data['user'], $data['password']);

            $user = $this->repository->find($auth->getUser());

            if ($this->cryptoMethod->verify($auth->getPassword(), $user->password) === false) {
                throw new \RuntimeException('A senha está incorreta');
            }

            $token = array(
//                "iss" => "http://example.org",
//                "aud" => "http://example.com",
                'iat' => 1356999524,
                'nbf' => 1357000000
            );
            $token = $this->jwtGenerator->hash($token);

            $this->saveLog($user->id);

            return new AuthenticatedResponse($token);
        } catch (\Exception $e) {
            return new NotPermittedResponse($e->getMessage());
        }
    }

    public function saveLog(int $user_id): void
    {
        try {
            $this->repository->logAccess($user_id);
        } catch (\Exception $e) {
            var_dump($e->getMessage());exit;
            //TODO Controle de exceção.
        }
    }
}
