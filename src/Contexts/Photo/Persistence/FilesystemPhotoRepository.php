<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Persistence;

use Intervention\Image\ImageManager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Like;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Publisher;

class FilesystemPhotoRepository implements PhotoRepository
{
    public function create(Photo $photo): Photo
    {
        try {

            $shared_path    = $_ENV['SHARED_PATH'];
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

            $filesystem = new Filesystem($localAdapter);
            $filesystem->createDir($photo->getFilePath('protected'));
            $filesystem->createDir($photo->getFilePath('thumb'));
            $filesystem->createDir($photo->getFilePath('watermark'));

            // save original
            $stream = fopen($photo->file['tmp_name'], 'r+');
            $file_path = $photo->getFilePath('protected', false, true);
            $filesystem->writeStream($file_path, $stream);
            fclose($stream);

            // create an image manager instance with favored driver
            $manager = new ImageManager();

            // thumb
            $protected_target_file = $photo->getFilePath('protected', true, true);
            $image = $manager->make($protected_target_file)->resize(null, 847, function ($constraint) {
                $constraint->aspectRatio();
            });
            $thumb_target_file = $photo->getFilePath('thumb', true, true);
            $image->save($thumb_target_file, 40);

            // watermark
            $watermark_target_file =  $photo->getFilePath('watermark', true, true);
            $image = $manager->make($thumb_target_file)->insert($photo->getWatermarkFile(), 'center-center', 0, 0);
            $image->save($watermark_target_file, 40);

            return $photo;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function rollback(Photo $photo)
    {
        //TODO: rollback
    }

    public function find(int $id): Photo
    {
        // TODO: Implement find() method.
    }

    public function download(Download $download): Download
    {
        // TODO: Implement download() method.
    }

    public function like(Like $like): Like
    {
        // TODO: Implement like() method.
    }

    public function dislike(Like $like): Like
    {
        // TODO: Implement dislike() method.
    }

    public function findPhotoOwner(Photo $photo): Photographer
    {
        // TODO: Implement findPhotoOwner() method.
    }

    public function findPublisher(int $publisher_id): Publisher
    {
        // TODO: Implement findPublisher() method.
    }
}