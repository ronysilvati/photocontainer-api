<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Helper;

use Intervention\Image\ImageManager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as CacheStore;
use League\Flysystem\Filesystem;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;

class EventPhotoHelper
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var EnqueueHelper
     */
    private $enqueueHelper;

    /**
     * EventPhotoHelper constructor.
     * @param null|EnqueueHelper $enqueueHelper
     */
    public function __construct(?EnqueueHelper $enqueueHelper = null)
    {
        $shared_path    = getenv('SHARED_PATH');
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

        $cacheStore = new CacheStore();
        $cachedAdapter = new CachedAdapter($localAdapter, $cacheStore);

        $this->filesystem = new Filesystem($cachedAdapter);

        $this->enqueueHelper = $enqueueHelper;
    }

    /**
     * @param Photo $photo
     * @return Photo
     * @throws PersistenceException
     */
    public function create(Photo $photo): Photo
    {
        try {

            $this->filesystem->createDir($photo->getFilePath('protected'));
            $this->filesystem->createDir($photo->getFilePath('thumb'));
            $this->filesystem->createDir($photo->getFilePath('watermark'));

            // save original
            $stream = fopen($photo->file['tmp_name'], 'r+');
            $file_path = $photo->getFilePath('protected', false, true);
            $this->filesystem->writeStream($file_path, $stream);
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
            $this->enqueueHelper->queueMessage(
                json_encode([
                    'type' => 'watermark',
                    'watermark_target_file' => $photo->getFilePath('watermark', true, true),
                    'thumb_target_file' => $thumb_target_file,
                    'watermark_file' => $photo->getWatermarkFile()
                ]),
                'image_processor'
            );

            return $photo;
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível gravar a foto.', $e->getMessage());
        }
    }

    /**
     * @param Photo $photo
     * @throws PersistenceException
     */
    public function deletePhoto(Photo $photo)
    {
        try {
            unlink($photo->getFilePath('thumb', true, true));
            unlink($photo->getFilePath('protected', true, true));
            unlink($photo->getFilePath('watermark', true, true));
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível apagar a foto.', $e->getMessage());
        }
    }
}
