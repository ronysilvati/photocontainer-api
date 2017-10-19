<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class Event
{
    private $id;
    private $bride;
    private $groom;
    private $eventDate;
    private $title;
    private $description;
    private $terms;
    private $approval_general;
    private $approval_photographer;
    private $approval_bride;
    private $photographer;
    private $categories;
    private $tags;
    private $favorites;
    private $country;
    private $state;
    private $city;

    /**
     * @var Suppliers
     */
    private $suppliers;

    public function __construct(int $id = null,
                                Photographer $photographer,
                                string $bride,
                                string $groom = null,
                                string $eventDate = null,
                                string $title,
                                string $description = null,
                                string $country = null,
                                string $state = null,
                                string $city = null,
                                bool $terms = null,
                                bool $approval_general = null,
                                bool $approval_photographer = null,
                                bool $approval_bride = null,
                                array $categories,
                                array $tags,
                                Suppliers $suppliers)
    {
        $this->changeId($id);
        $this->changeTitle($title);
        $this->changeBride($bride);
        $this->changeGroom($groom);
        $this->changeTitle($title);
        $this->changeDescription($description);
        $this->changeEventDate($eventDate);
        $this->changeTerms($terms);
        $this->changeApprovalGeneral($approval_general);
        $this->changeApprovalBride($approval_bride);
        $this->changeApprovalPhotographer($approval_photographer);
        $this->changePhotographer($photographer);
        $this->changeCategories($categories);
        $this->changeTags($tags);
        $this->changeSuppliers($suppliers);

        $this->changeCountry($country);
        $this->changeState($state);
        $this->changeCity($city);
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function changeId(?int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getBride(): string
    {
        return $this->bride;
    }

    /**
     * @param mixed $bride
     */
    public function changeBride(string $bride)
    {
        $this->bride = $bride;
    }

    /**
     * @return mixed
     */
    public function getGroom(): string
    {
        return $this->groom;
    }

    /**
     * @param string|null $groom
     */
    public function changeGroom(string $groom = null)
    {
        $this->groom = $groom;
    }

    /**
     * @return string
     */
    public function getEventDate(): string
    {
        return $this->eventDate;
    }

    /**
     * @param string $eventDate
     */
    public function changeEventDate(string $eventDate)
    {
        $this->eventDate = $eventDate;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function changeTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function changeDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTerms(): string
    {
        return $this->terms;
    }

    /**
     * @param mixed $terms
     */
    public function changeTerms(bool $terms = null)
    {
        if ($terms === null || $terms === false) {
            throw new \DomainException('Os termos devem ser aceitos.');
        }

        $this->terms = $terms;
    }

    /**
     * @return bool
     */
    public function getApprovalGeneral(): bool
    {
        return $this->approval_general;
    }

    /**
     * @param mixed $approval_general
     */
    public function changeApprovalGeneral(bool $approval_general)
    {
        $this->approval_general = $approval_general;
    }

    /**
     * @return bool
     */
    public function getApprovalPhotographer(): bool
    {
        return $this->approval_photographer;
    }

    /**
     * @param mixed $approval_photographer
     */
    public function changeApprovalPhotographer(bool $approval_photographer)
    {
        $this->approval_photographer = $approval_photographer;
    }

    /**
     * @return bool
     */
    public function getApprovalBride(): bool
    {
        return $this->approval_bride;
    }

    /**
     * @param mixed $approval_bride
     */
    public function changeApprovalBride(bool $approval_bride)
    {
        $this->approval_bride = $approval_bride;
    }

    /**
     * @return Photographer
     */
    public function getPhotographer(): Photographer
    {
        return $this->photographer;
    }

    /**
     * @param Photographer $photographer
     */
    public function changePhotographer(Photographer $photographer)
    {
        $this->photographer = $photographer;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function changeCategories(array $categories)
    {
        if (empty($categories)) {
            throw new \DomainException('Deve ser enviada ao menos uma categoria.');
        }

        if (count($categories) > 1) {
            throw new \DomainException('É permitido apenas uma categoria.');
        }

        $this->categories = $categories;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param $tags
     */
    public function changeTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getFavorites(): ?array
    {
        return $this->tags;
    }

    /**
     * @param array $favorites
     */
    public function changeFavorites(array $favorites)
    {
        $this->favorites = $favorites;
    }

    /**
     * @return Suppliers
     */
    public function getSuppliers(): ?Suppliers
    {
        return $this->suppliers;
    }

    /**
     * @param Suppliers $suppliers
     */
    public function changeSuppliers(?Suppliers $suppliers)
    {
        $this->suppliers = $suppliers;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function changeCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function changeState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function changeCity($city)
    {
        $this->city = $city;
    }
}
