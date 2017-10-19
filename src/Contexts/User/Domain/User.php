<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;


use PhotoContainer\PhotoContainer\Contexts\User\Event\PublisherCreated;
use PhotoContainer\PhotoContainer\Contexts\User\Event\UserCreated;

use PhotoContainer\PhotoContainer\Infrastructure\Event\EventRecorder;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use PhotoContainer\PhotoContainer\Infrastructure\Validation\Validator;

class User
{
    use Validator;

    protected $name;
    protected $email;
    protected $details;
    protected $profile;
    protected $pwd;
    protected $id;
    protected $address;

    /**
     * User constructor.
     * @param int|null $id
     * @param string|null $name
     * @param string|null $email
     * @param string|null $pwd
     * @param Details|null $details
     * @param Profile|null $profile
     * @throws DomainViolationException
     */
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

    /**
     * @param string $name
     * @throws DomainViolationException
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     */
    public function changeName(string $name): void
    {
        if (!$this->validateLength($name, 3, 150)) {
            throw new DomainViolationException('O nome está em um formato inválido!');
        }

        $this->name = $name;
    }

    /**
     * @param string $email
     * @throws DomainViolationException
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     */
    public function changeEmail(string $email): void
    {
        if (!$this->validateEmail($email)) {
            throw new DomainViolationException('O email está em um formato inválido!');
        }
        $this->email = $email;
    }

    /**
     * @param string|null $blog
     * @throws DomainViolationException
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     */
    public function changeBlog(string $blog = null): void
    {
        if ($this->getProfile()->getProfileId() == Profile::PUBLISHER && null === $blog) {
            throw new DomainViolationException('O endereço do blog deve ser enviado!');
        }

        $this->getDetails()->changeBlog($blog);
    }

    /**
     * @param Details|null $details
     * @throws DomainViolationException
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     */
    public function changeDetails(Details $details = null): void
    {
        if ($this->getProfile()->getProfileId() === Profile::PUBLISHER && null === $details->getBlog()) {
            throw new DomainViolationException('O endereço do blog deve ser enviado!');
        }

        $this->details = $details;
    }

    /**
     * @param string $pwd
     * @throws \DomainException
     */
    public function changePwd(string $pwd): void
    {
        if (empty($pwd)) {
            throw new \DomainException('A senha nâo pode ser vazia');
        }

        $this->pwd = $pwd;
    }

    /**
     * @param int $id
     */
    public function changeId(int $id): void
    {
        $this->id = $id;

        EventRecorder::getInstance()->record(new UserCreated($this));

        if ($this->getProfile()->getProfileId() === Profile::PUBLISHER) {
            EventRecorder::getInstance()->record(new PublisherCreated($this));
        }
    }

    public function changeProfile(Profile $profile): void
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
    public function getDetails(): Details
    {
        return $this->details;
    }

    /**
     * @return null|string
     */
    public function getPwd(): ?string
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
     * @return Profile
     */
    public function getProfile(): Profile
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
    public function changeAddress(?Address $address): void
    {
        $this->address = $address;
    }
}
