<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Helper;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as CacheStore;
use League\Flysystem\Filesystem;

class ImageHelper
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $criteria;

    /**
     * @var string
     */
    private $dir;

    private $errMessage = "Não foi possível processar a imagem.";

    const CRITERIA_DIMENSIONS = 'dimensions';
    const CRITERIA_MIMETYPE = 'mimetype';

    public function __construct(string $dir)
    {
        $localAdapter = new Local($dir, LOCK_EX, Local::DISALLOW_LINKS, [
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

        $this->dir = $dir;
    }

    /**
     * @param string $file
     * @param string $filename
     * @param int $quality
     * @return null|string
     */
    public function createImage(string $file, string $filename, int $quality = 40): ?string
    {
        $targetfile = $this->dir.$filename;

        if ($this->filesystem->has($targetfile)) {
            $this->filesystem->delete($targetfile);
        }

        $manager = new ImageManager();
        $image = $manager->make($file);

        if ($this->criteria) {
            foreach ($this->criteria as $type => $data) {
                if (!$this->executeCriteria($type, $image)) {
                    return null;
                }
            }
        }

        $image->save($targetfile, $quality);

        return $targetfile;
    }

    /**
     * @param string $type
     * @param array $criteria
     */
    public function addCriteriaForSaving(string $type, array $criteria)
    {
        $this->criteria[$type] = $criteria;
    }

    private function executeCriteria(string $type, Image $image): bool
    {
        $criteria = $this->criteria[$type];
        switch ($type) {
            case self::CRITERIA_MIMETYPE:
                $passed = $this->passMimetypeCriteria($image, $criteria);
                break;

            case self::CRITERIA_DIMENSIONS:
                $passed = $this->passDimensionCriteria($image, $criteria);
                break;
            default:
                throw new \Exception('Critério inexistente!');
                break;
        }

        $this->errMessage = $passed ? '' : $this->criteria[$type]['errMsg'];
        return $passed;
    }

    /**
     * @param Image $image
     * @param array $criteria
     * @return bool
     */
    private function passDimensionCriteria(Image $image, array $criteria): bool
    {
        return $image->getHeight() == $criteria['height'] && $image->getWidth() == $criteria['width'];
    }

    /**
     * @param Image $image
     * @param array $criteria
     * @return bool
     */
    private function passMimetypeCriteria(Image $image, array $criteria): bool
    {
        return in_array($image->mime(), $criteria['mimetypes']);
    }

    /**
     * @return string
     */
    public function getErrMessage(): string
    {
        return $this->errMessage;
    }
}
