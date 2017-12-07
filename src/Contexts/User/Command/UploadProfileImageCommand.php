<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Command;


use Psr\Http\Message\UploadedFileInterface;

class UploadProfileImageCommand
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var UploadedFileInterface
     */
    private $file;

    /**
     * UploadProfileImageCommand constructor.
     * @param int $userId
     * @param UploadedFileInterface $file
     */
    public function __construct(int $userId, UploadedFileInterface $file)
    {
        $this->userId = $userId;
        $this->file = $file;
    }

    /**
     * @return UploadedFileInterface
     */
    public function getFile(): UploadedFileInterface
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}