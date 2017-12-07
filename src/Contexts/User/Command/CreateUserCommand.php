<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Command;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;

class CreateUserCommand
{
    /**
     * @var User
     */
    private $user;

    /**
     * CreateUserCommand constructor.
     * @param array $bodyData
     */
    public function __construct(array $bodyData)
    {
        $details = new Details(
            null,
            $bodyData['details']['blog'] ?? '',
            $bodyData['details']['instagram'] ?? '',
            $bodyData['details']['facebook'] ?? '',
            $bodyData['details']['pinterest'] ?? '',
            $bodyData['details']['site'] ?? '',
            $bodyData['details']['phone'] ?? '',
            $bodyData['details']['birth'] ?? ''
        );

        $profile = new Profile(null, null, (int) $bodyData['profile'], null);
        $this->user = new User(null, $bodyData['name'], $bodyData['email'], $bodyData['password'], $details, $profile);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}