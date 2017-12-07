<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\SearchEngine;

use PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper;
use Illuminate\Database\Eloquent\Model;

class ResourceLoader
{
    /**
     * @var CacheHelper
     */
    private $cacheHelper;

    public function __construct(CacheHelper $cacheHelper)
    {
        $this->cacheHelper = $cacheHelper;
    }

    /**
     * @param string $resource
     * @return Model
     * @throws \RuntimeException
     */
    public function load(string $resource): Model
    {
        $resources =  $this->cacheHelper->remember('search_engine_file', function () {
            $filename = ROOT_DIR.'/src/Application/Resources/search_engine.yml';
            return yaml_parse_file($filename);
        });

        if (!isset($resources['resources'][$resource])) {
            throw new \RuntimeException('Resource not defined.');
        }

        return new $resources['resources'][$resource];
    }
}