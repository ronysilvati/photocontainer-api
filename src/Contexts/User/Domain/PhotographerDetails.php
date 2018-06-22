<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

use League\Flysystem\Exception;

use PhotoContainer\PhotoContainer\Infrastructure\Validation\Validator;

class PhotographerDetails
{
    use Validator;

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
    private $name_type;

    const BY_NAME = 'name';
    const BY_STUDIO = 'studio';

    /**
     * PhotographerDetails constructor.
     * @param string $bio
     * @param string $studio
     * @param string $name_type
     * @throws Exception
     */
    public function __construct(?string $bio, ?string $studio, string $name_type)
    {
        $this->bio = $bio;
        $this->studio = $studio;

        $this->setNameType($name_type);
    }

    /**
     * @return string
     */
    public function getBio(): ?string
    {
        return $this->bio;
    }

    /**
     * @return string
     */
    public function getStudio(): ?string
    {
        return $this->studio;
    }

    /**
     * @return string
     */
    public function getNameType(): string
    {
        return $this->name_type;
    }

    /**
     * @param string $name_type
     * @throws Exception
     * @throws \League\Flysystem\Exception
     */
    public function setNameType(string $name_type): void
    {
        if (!in_array($name_type, [self::BY_NAME, self::BY_STUDIO], true)) {
            throw new Exception('Aceitos: name, studio');
        }

        $this->name_type = $name_type;
    }
}
