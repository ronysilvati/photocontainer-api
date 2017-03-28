<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\DomainExceptionResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Entity;

class UpdateUser
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(int $id, array $data, string $crypto = null)
    {
        try {
            $user = $this->userRepository->findUser($id);

            if (isset($data['name'])) {
                $user->changeName($data['name']);
            }

            if (isset($data['email'])) {
                $user->changeEmail($data['email']);
            }

            if (isset($data['details']['blog'])) {
                $user->getDetails()->changeBlog($data['details']['blog']);
            }

            if (isset($data['details']['facebook'])) {
                $user->getDetails()->changeFacebook($data['details']['facebook']);
            }

            if (isset($data['details']['linkedin'])) {
                $user->getDetails()->changeLinkedin($data['details']['linkedin']);
            }

            if (isset($data['details']['instagram'])) {
                $user->getDetails()->changeInstagram($data['details']['instagram']);
            }

            if (isset($data['details']['gender'])) {
                $user->getDetails()->changeGender($data['details']['gender']);
            }

            if (isset($data['details']['phone'])) {
                $user->getDetails()->changePhone($data['details']['phone']);
            }

            if (isset($data['details']['birth'])) {
                $user->getDetails()->changeBirth($data['details']['birth']);
            }

            if (isset($data['details']['site'])) {
                $user->getDetails()->changeSite($data['details']['site']);
            }

            $user = $this->userRepository->updateUser($user, $crypto);
            return new UserResponse($user);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}