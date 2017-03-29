<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

class Address
{
    private $id;
    private $user_id;
    private $zipcode;
    private $country;
    private $state;
    private $city;
    private $neighborhood;
    private $street;
    private $complement;

    /**
     * Cep constructor.
     * @param $id
     * @param $user_id
     * @param $zipcode
     * @param $country
     * @param $state
     * @param $city
     * @param $neighborhood
     * @param $street
     * @param $complement
     */
    public function __construct(
        ?int $id,
        ?int $user_id,
        ?string $zipcode,
        ?string $country,
        ?string $state,
        ?string $city,
        ?string $neighborhood,
        ?string $street,
        ?string $complement)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->zipcode = $zipcode;
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->neighborhood = $neighborhood;
        $this->street = $street;
        $this->complement = $complement;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function changeId(?int $id): ?int
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function changeUserId(?int $user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return null|string
     */
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    /**
     * @param null|string $zipcode
     */
    public function changeZipcode(?string $zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param null|string $country
     */
    public function changeCountry(?string $country)
    {
        $this->country = $country;
    }

    /**
     * @return null|string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param null|string $state
     */
    public function changeState(?string $state)
    {
        $this->state = $state;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     */
    public function changeCity(?string $city)
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    /**
     * @param null|string $neighborhood
     */
    public function changeNeighborhood(?string $neighborhood)
    {
        $this->neighborhood = $neighborhood;
    }

    /**
     * @return null|string
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param null|string $street
     */
    public function changeStreet(?string $street)
    {
        $this->street = $street;
    }

    /**
     * @return null|string
     */
    public function getComplement(): ?string
    {
        return $this->complement;
    }

    /**
     * @param null|string $complement
     */
    public function changeComplement(?string $complement)
    {
        $this->complement = $complement;
    }
}