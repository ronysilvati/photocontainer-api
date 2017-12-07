<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Command;

class UpdateUserCommand
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $profileId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $blog;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $bio;

    /**
     * @var string
     */
    private $studio;

    /**
     * @var string
     */
    private $nameType;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $zipcode;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $neighborhood;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $complement;

    /**
     * @var string
     */
    private $facebook;

    /**
     * @var string
     */
    private $pinterest;

    /**
     * @var string
     */
    private $instagram;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $birth;

    /**
     * @var string
     */
    private $site;

    /**
     * @var bool
     */
    private $hasDetails;

    /**
     * @var bool
     */
    private $hasAddress;

    /**
     * UpdateUserCommand constructor.
     * @param int $id
     * @param array $bodyData
     */
    public function __construct(int $id, array $bodyData)
    {
        $this->id = $id;

        $this->name = $bodyData['name'] ?? null;
        $this->email = $bodyData['email'] ?? null;
        $this->profileId = $bodyData['profile_id'] ?? null;

        $this->password = $bodyData['password'] ?? null;
        if ($this->password === '') {
            $this->password = null;
        }

        $this->hasDetails = false;
        if (isset($bodyData['details'])) {
            $this->hasDetails = true;

            $this->blog = $bodyData['blog'] ?? null;
            $this->facebook = $bodyData['facebook'] ?? null;
            $this->pinterest = $bodyData['pinterest'] ?? null;
            $this->instagram = $bodyData['instagram'] ?? null;
            $this->phone = $bodyData['phone'] ?? null;
            $this->birth = $bodyData['birth'] ?? null;
            $this->site = $bodyData['site'] ?? null;
            $this->bio = $bodyData['bio'] ?? '';
            $this->studio = $bodyData['studio'] ?? '';
            $this->nameType = $bodyData['name_type'] ?? '';
        }

        $this->hasAddress = false;
        if (isset($bodyData['address'])) {
            $this->hasAddress = true;

            $this->country = $bodyData['country'] ?? null;
            $this->zipcode = $bodyData['zipcode'] ?? null;
            $this->state = $bodyData['state'] ?? null;
            $this->city = $bodyData['city'] ?? null;
            $this->neighborhood = $bodyData['neighborhood'] ?? null;
            $this->street = $bodyData['street'] ?? null;
            $this->complement = $bodyData['complement'] ?? null;
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getProfileId(): ?int
    {
        return $this->profileId;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return null|string
     */
    public function getBlog(): ?string
    {
        return $this->blog;
    }

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return null|string
     */
    public function getBio(): ?string
    {
        return $this->bio;
    }

    /**
     * @return null|string
     */
    public function getStudio(): ?string
    {
        return $this->studio;
    }

    /**
     * @return null|string
     */
    public function getNameType(): ?string
    {
        return $this->nameType;
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return null|string
     */
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    /**
     * @return null|string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return null|string
     */
    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    /**
     * @return null|string
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @return null|string
     */
    public function getComplement(): ?string
    {
        return $this->complement;
    }

    /**
     * @return null|string
     */
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    /**
     * @return null|string
     */
    public function getPinterest(): ?string
    {
        return $this->pinterest;
    }

    /**
     * @return null|string
     */
    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return null|string
     */
    public function getBirth(): ?string
    {
        return $this->birth;
    }

    /**
     * @return null|string
     */
    public function getSite(): ?string
    {
        return $this->site;
    }

    /**
     * @return bool
     */
    public function isHasDetails(): bool
    {
        return $this->hasDetails;
    }

    /**
     * @return bool
     */
    public function isHasAddress(): bool
    {
        return $this->hasAddress;
    }
}