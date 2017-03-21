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
    private $linkedin;
    private $site;
    private $gender;
    private $phone;
    private $birth;

    /**
     * Details constructor.
     * @param $id
     * @param $blog
     * @param $instagram
     * @param $facebook
     * @param $linkedin
     * @param $site
     * @param $gender
     * @param $phone
     * @param $birth
     */
    public function __construct(int $id = null,
                                string $blog = null,
                                string $instagram = null,
                                string $facebook = null,
                                string $linkedin = null,
                                string $site = null,
                                string $gender = null,
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

        if ($linkedin) {
            $this->changeLinkedin($linkedin);
        }

        if ($site) {
            $this->changeSite($site);
        }

        if ($gender) {
            $this->changeGender($gender);
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
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * @param mixed $linkedin
     */
    public function changeLinkedin($linkedin)
    {
        if ($linkedin != "" && !$this->validateUrl($linkedin)) {
            throw new \DomainException("A URL do LinkedIn é inválida!");
        }

        $this->linkedin = $linkedin;
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
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function changeGender($gender)
    {
        if (!in_array(strtoupper($gender), ['M', 'F'])) {
            throw new \DomainException("O gênero é inválido!");
        }

        $this->gender = $gender;
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


}