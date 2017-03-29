<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use PhotoContainer\PhotoContainer\Infrastructure\Entity;
use PhotoContainer\PhotoContainer\Infrastructure\Validation\Validator;

class User implements Entity
{
    use Validator;

    protected $name;
    protected $email;
    protected $details;
    protected $profile;
    protected $pwd;
    protected $id;
    protected $address;

    public function __construct(int $id = null, string $name = null, string $email = null, string $pwd = null, Details $details = null, Profile $profile = null)
    {
        $this->id = $id;
        $this->pwd = $pwd;

        $this->changeEmail($email);
        $this->changeName($name);

        if ($profile) {
            $this->changeProfile($profile);
        }

        if ($details) {
            $this->changeDetails($details);
        }
    }

    public function changeName(string $name)
    {
        if (!$this->validateLength($name, 3, 150)) {
            throw new DomainViolationException("O nome está em um formato inválido!");
        }

        $this->name = $name;
    }

    public function changeEmail(string $email)
    {
        if (!$this->validateEmail($email)) {
            throw new DomainViolationException("O email está em um formato inválido!");
        }
        $this->email = $email;
    }

    public function changeBlog(string $blog = null)
    {
        if ($this->getProfile()->getProfileId() == Profile::PUBLISHER && empty($blog)) {
            throw new DomainViolationException("O endereço do blog deve ser enviado!");
        }

        $this->getDetails()->changeBlog($blog);
    }

    public function changeDetails(Details $details = null)
    {
        if ($this->getProfile()->getProfileId() === Profile::PUBLISHER && empty($details->getBlog())) {
            throw new DomainViolationException("O endereço do blog deve ser enviado!");
        }

        $this->details = $details;
    }

    public function changePwd(string $pwd)
    {
        if (empty($pwd)) {
            throw new \DomainException("A senha nâo pode ser vazia");
        }

        $this->pwd = $pwd;
    }

    public function changeId(int $id)
    {
        $this->id = $id;
    }

    public function changeProfile(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return string
     */
    public function getPwd(): string
    {
        return $this->pwd;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @return mixed
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function changeAddress(?Address $address)
    {
        $this->address = $address;
    }


}