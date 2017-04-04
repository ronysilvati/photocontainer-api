<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use Intervention\Image\ImageManager;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class FilesystemPhotoRepository implements PhotoRepository
{
    public function create(Photo $photo): Photo
    {
        try {
            // temp pra pasta final
            $photo->changePhysicalName($photo->file['name']);

            $shared_path    = $_ENV['SHARED_PATH'];
            $watermark_path = $shared_path . '/watermark.png';

            $localAdapter = new Local($shared_path, LOCK_EX, Local::DISALLOW_LINKS, [
                'file' => [
                    'public' => 0744,
                    'private' => 0700,
                ],
                'dir' => [
                    'public' => 0755,
                    'private' => 0700,
                ]
            ]);
            $event_dir = 'event/'.$photo->getEventId();
            $filesystem = new Filesystem($localAdapter);
            $filesystem->createDir($event_dir);

            // save original
            $stream = fopen($photo->file['tmp_name'], 'r+');
            $file_path = $event_dir . '/' . $photo->getPhysicalName();
            $filesystem->writeStream($file_path, $stream);
            fclose($stream);

            // create an image manager instance with favored driver
            $manager = new ImageManager();

            // thumb
            $original_file = $shared_path . '/' . $event_dir . '/' . $photo->getPhysicalName();
            $image = $manager->make($original_file)->resize(null, 847, function ($constraint) {
                $constraint->aspectRatio();
            });
            $thumb_target_file =  $shared_path . '/' . $event_dir . '/T' . $photo->getPhysicalName();
            $image->save($thumb_target_file);

            // watermark
            $watermark_target_file =  $shared_path . '/' . $event_dir . '/W' . $photo->getPhysicalName();
            $image = $manager->make($thumb_target_file)->insert($watermark_path, 'right-right', 100, 100);
            $image->save($watermark_target_file);

            return $photo;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function rollback(Photo $photo)
    {
// TODO: implementar rollback
//        unlink($photo->getPhysicalName());
    }
}