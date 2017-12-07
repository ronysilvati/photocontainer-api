<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Helper;

use Ramsey\Uuid\Uuid;

class ProfileImageHelper
{
    /**
     * @var string
     */
    private $profile_images_dir;

    const EXTENSIONS = ['image/jpeg' => 'jpg', 'image/png' => 'png'];

    /**
     * ProfileImageHelper constructor.
     */
    public function __construct()
    {
        $this->profile_images_dir = getenv('PROFILE_IMAGE_PATH');
    }

    /**
     * @param int $user_id
     * @param string $mimetype
     * @return string
     */
    public function generateName(int $user_id, string $mimetype): string
    {
        return $this->generateFilename($user_id).'.'.self::EXTENSIONS[$mimetype];
    }

    /**
     * @param int $user_id
     * @return null|string
     */
    public function resolveUri(int $user_id): ?string
    {
        $list = $this->findProfileImage($user_id);
        if (empty($list)) {
            return null;
        }

        $parts = explode('/', $list[0]);
        return 'profile_images/'.end($parts).'?_t='.time();
    }

    /**
     * @param int $user_id
     * @return string
     */
    private function generateFilename(int $user_id): string
    {
        return $user_id.'-'.Uuid::uuid4()->toString();
    }

    /**
     * @param int $user_id
     * @return array|null
     */
    public function findProfileImage(int $user_id): ?array
    {
        $list = glob($this->profile_images_dir.'/'.$user_id.'-*.*');
        return empty($list) ? null : $list;
    }

    /**
     * @param int $user_id
     */
    public function removeOldVersions(int $user_id): void
    {
        $list = $this->findProfileImage($user_id);
        if (empty($list)) {
            return;
        }

        foreach ($list as $file) {
            unlink($file);
        }
    }
}