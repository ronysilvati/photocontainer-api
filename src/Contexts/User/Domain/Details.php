<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;


use PhotoContainer\PhotoContainer\Infrastructure\Validation\Validator;

class Details
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

        $this->changeBlog($blog);
        $this->changeInstagram($instagram);
        $this->changeFacebook($facebook);
        $this->changePinterest($pinterest);
        $this->changeSite($site);
        $this->changePhone($phone);
        $this->changeBirth($birth);
    }

    public function changeBlog(?string $blog)
    {
        if ($blog && empty($blog)) {
            throw new \DomainException('A URL do blog deve ser enviada!');
        }

        $this->blog = $blog;
    }

    public function changeId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getBlog(): ?string
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
     * @return null|string
     */
    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    /**
     * @param null|string $instagram
     */
    public function changeInstagram(?string $instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * @return null|string
     */
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    /**
     * @param null|string $facebook
     */
    public function changeFacebook(?string $facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return null|string
     */
    public function getPinterest(): ?string
    {
        return $this->pinterest;
    }

    /**
     * @param null|string $pinterest
     */
    public function changePinterest(?string $pinterest)
    {
        $this->pinterest = $pinterest;
    }

    /**
     * @return null|string
     */
    public function getSite(): ?string
    {
        return $this->site;
    }

    /**
     * @param null|string $site
     */
    public function changeSite(?string $site)
    {
        $this->site = $site;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function changePhone(?string $phone)
    {
        $this->phone = $phone ?? '';
    }

    /**
     * @return null|string
     */
    public function getBirth(): ?string
    {
        return $this->birth;
    }

    /**
     * @param mixed $birth
     */
    public function changeBirth(?string $birth)
    {
        $this->birth = $birth ?? '';
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
