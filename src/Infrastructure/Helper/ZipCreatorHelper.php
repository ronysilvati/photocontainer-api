<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Helper;

use \ZipArchive;

class ZipCreatorHelper
{
    /**
     * @param string $name
     * @param array $filesForZip
     * @return bool
     * @throws \RuntimeException
     */
    public function createFromFiles(string $name, array $filesForZip): bool
    {
        try {
            $zip = new ZipArchive();
            $zip->open($name, ZipArchive::CREATE);

            foreach ($filesForZip as $filepath) {
                $parts = explode('/', $filepath);

                if (!file_exists($filepath) || !$zip->addFile($filepath, end($parts))) {
                    throw new \RuntimeException('Could not add file do zip.');
                }
            }

            return $zip->close();
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}