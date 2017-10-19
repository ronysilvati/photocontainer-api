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
        ?int $id = null,
        ?int $user_id = null,
        ?string $zipcode = null,
        ?string $country = null,
        ?string $state = null,
        ?string $city = null,
        ?string $neighborhood = null,
        ?string $street = null,
        ?string $complement = null)
    {
        $this->id = $id;
        $this->user_id = $user_id;

        $this->changeCountry($country);
        $this->changeZipcode($zipcode);
        $this->changeState($state);
        $this->changeCity($city);
        $this->changeNeighborhood($neighborhood);
        $this->changeStreet($street);
        $this->changeComplement($complement);
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
    public function changeId(?int $id)
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
     * @throws \Exception
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
     * @throws \Exception
     */
    public function changeCountry(?string $country)
    {
        if (!empty($this->zipcode) && $country === '') {
            throw new \Exception('O campo País deve ser enviado.');
        }

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
     * @throws \Exception
     */
    public function changeState(?string $state)
    {
        if (!empty($this->zipcode) && $state === '') {
            throw new \Exception('O campo Estado deve ser enviado.');
        }

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
     * @throws \Exception
     */
    public function changeCity(?string $city)
    {
        if (!empty($this->zipcode) && $city === '') {
            throw new \Exception('O campo Cidade deve ser enviado.');
        }

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
     * @throws \Exception
     */
    public function changeNeighborhood(?string $neighborhood)
    {
        if (!empty($this->zipcode) && $neighborhood === '') {
            throw new \Exception('O campo Bairro deve ser enviado.');
        }

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
     * @throws \Exception
     */
    public function changeStreet(?string $street)
    {
        if (!empty($this->zipcode) && $street === '') {
            throw new \Exception('O campo Logradouro deve ser enviado.');
        }

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
