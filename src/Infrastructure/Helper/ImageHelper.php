<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Helper;

use Intervention\Image\ImageManager;

class ImageHelper
{
    private $manager;

    public function __construct()
    {
        $this->manager = new ImageManager();
    }

    public function loadImageInstance($filename)
    {
        try {
            return $this->manager->make($filename);
        } catch (\Exception $e) {
            throw new \Exception("Ocorreu um erro de leitura da imagem!");
        }
    }
}