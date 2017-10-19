<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;


use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use Ramsey\Uuid\Uuid;

class ProfileImage
{
    /**
     * @var int
     */
    protected $user_id;

    /**
     * @var array
     */
    protected $file;

    const ALLOWED_FILES = ['image/jpeg', 'image/png'];

    /**
     * ProfileImage constructor.
     * @param int $user_id
     * @param array $file
     * @throws DomainViolationException
     */
    public function __construct(int $user_id, array $file)
    {
        if ($user_id === 0) {
            throw new DomainViolationException('O ID do usuário é inválido.');
        }

        $this->user_id = $user_id;
        $this->changeFile($file);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function changeFile(array $file)
    {
        if (!in_array($file['type'], self::ALLOWED_FILES)) {
            throw new DomainViolationException('Formato não preenchido.');
        }

        if ($file['error'] > 0) {
            throw new DomainViolationException('Erro no upload da imagem.');
        }

        $this->file = $file;
    }

    public function getImageName(): string
    {
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        return Uuid::uuid5(Uuid::NAMESPACE_DNS, $this->user_id)->toString().'.'.$extensions[$this->file['type']];
    }

    public function getFile(): string
    {
        return $this->file['tmp_name'];
    }
}
