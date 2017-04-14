<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

use PhotoContainer\PhotoContainer\Infrastructure\Entity;
use PhotoContainer\PhotoContainer\Infrastructure\Validation\Validator;

class Details implements Entity
{
    use Validator;

    private $id;
    private $blog;
    private $instagram;
    private $facebook;
    private $pinterest;
    private $site;
    private $phone;
    private $birth;

    /**
     * @var PhotographerDetails
     */
    private $phographerDetails;

    /**
     * Details constructor.
     * @param int|null $id
     * @param string|null $blog
     * @param string|null $instagram
     * @param string|null $facebook
     * @param string|null $pinterest
     * @param string|null $site
     * @param string|null $phone
     * @param string|null $birth
     */
    public function __construct(int $id = null,
                                string $blog = null,
                                string $instagram = null,
                                string $facebook = null,
                                string $pinterest = null,
                                string $site = null,
                                string $phone = null,
                                string $birth = null)
    {
        $this->id = $id;

        if ($blog) {
            $this->changeBlog($blog);
        }

        if ($instagram) {
            $this->changeInstagram($instagram);
        }

        if ($facebook) {
            $this->changeFacebook($facebook);
        }

        if ($pinterest) {
            $this->changePinterest($pinterest);
        }

        if ($site) {
            $this->changeSite($site);
        }

        if ($phone) {
            $this->changePhone($phone);
        }

        if ($birth) {
            $this->changeBirth($birth);
        }
    }

    public function changeBlog(string $blog)
    {
        if (!empty($blog) && !$this->validateUrl($blog)) {
            throw new \DomainException("A URL é inválida!");
        }

        $this->blog = $blog;
    }

    public function changeId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getBlog()
    {
        return $this->blog;
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
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param mixed $instagram
     */
    public function changeInstagram($instagram)
    {
        if (!empty($instagram) && !$this->validateUrl($instagram)) {
            throw new \DomainException("A URL do Instagram é inválida!");
        }

        $this->instagram = $instagram;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     */
    public function changeFacebook($facebook)
    {
        if ($facebook != "" && !$this->validateUrl($facebook)) {
            throw new \DomainException("A URL do Facebook é inválida!");
        }

        $this->facebook = $facebook;
    }

    /**
     * @return mixed
     */
    public function getPinterest()
    {
        return $this->pinterest;
    }

    /**
     * @param $pinterest
     */
    public function changePinterest($pinterest)
    {
        if ($pinterest != "" && !$this->validateUrl($pinterest)) {
            throw new \DomainException("A URL do Pinterest é inválida!");
        }

        $this->pinterest = $pinterest;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function changeSite($site)
    {
        if ($site != "" && !$this->validateUrl($site)) {
            throw new \DomainException("A URL do Site é inválida!");
        }

        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function changePhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * @param mixed $birth
     */
    public function changeBirth($birth)
    {
        $this->birth = $birth;
    }

    /**
     * @return null|PhotographerDetails
     */
    public function getPhographerDetails(): ?PhotographerDetails
    {
        return $this->phographerDetails;
    }

    /**
     * @param null|PhotographerDetails $phographerDetails
     */
    public function changePhographerDetails(?PhotographerDetails $phographerDetails)
    {
        $this->phographerDetails = $phographerDetails;
    }
}