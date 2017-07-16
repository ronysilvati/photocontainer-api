<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Helper;

use Ramsey\Uuid\Uuid;

class ProfileImageHelper
{
    public function generateName($user_id, $file): string
    {
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        return $this->generateFilename($user_id).'.'.$extensions[$file['type']];
    }

    public function resolveUri($user_id): ?string
    {
        $list = glob(getenv('SHARED_PATH').'/profile_images/'.$user_id.'-*.*');
        if (empty($list)) {
            return null;
        }

        $parts = explode('/', $list[0]);

        return 'profile_images/'.end($parts).'?_t='.time();
    }

    private function generateFilename($user_id): string
    {
        return $user_id.'-'.Uuid::uuid4()->toString();
    }

    private function removeOldVersions($user_id)
    {
        $list = glob(getenv('SHARED_PATH').'/profile_images/'.$user_id.'-*.*');
        if (empty($list)) {
            return;
        }

        foreach ($list as $file) {
            unlink($file);
        }
    }
}