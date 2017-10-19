<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Domain;

class Cep
{
    private $zipcode;
    private $country;
    private $state;
    private $city;
    private $neighborhood;
    private $street;
    private $complement;

    /**
     * Cep constructor.
     * @param null|string $zipcode
     * @param null|string $country
     * @param null|string $state
     * @param null|string $city
     * @param null|string $neighborhood
     * @param null|string $street
     * @param null|string $complement
     */
    public function __construct(
        ?string $zipcode,
        ?string $country,
        ?string $state,
        ?string $city,
        ?string $neighborhood,
        ?string $street,
        ?string $complement)
    {
        $this->zipcode = $zipcode;
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->neighborhood = $neighborhood;
        $this->street = $street;
        $this->complement = $complement;
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
    public function changeZipcode(?string $zipcode): void
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
    public function changeCountry(?string $country): void
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
    public function changeState(?string $state): void
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
    public function changeCity(?string $city): void
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
    public function changeNeighborhood(?string $neighborhood): void
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
    public function changeStreet(?string $street): void
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
    public function changeComplement(?string $complement): void
    {
        $this->complement = $complement;
    }
}
