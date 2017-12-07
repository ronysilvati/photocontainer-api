<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\SearchEngine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class SearchEngine
{
    /**
     * @var ResourceLoader
     */
    private $resourceLoader;

    /**
     * @var Model
     */
    private $model;

    /**
     * SearchEngine constructor.
     * @param ResourceLoader $resourceLoader
     */
    public function __construct(ResourceLoader $resourceLoader)
    {
        $this->resourceLoader = $resourceLoader;
    }

    /**
     * @param string $resource
     * @return SearchEngine
     */
    public function addResource(string $resource): SearchEngine
    {
        $this->model = $this->resourceLoader->load($resource);
        return $this;
    }

    /**
     * @param array $queryString
     * @return array
     */
    public function query(array $queryString): array
    {
        $builder = Capsule::connection()->getSchemaBuilder();

        $query = [];
        foreach ($queryString as $field => $value) {
            $type = $builder->getColumnType($this->model->getTable(), $field);

            if ($type === 'string') {
                $query[] = [$field, 'like', $value];
            } else {
                $query[] = [$field, $value];
            }
        }

        return $this->model->where($query)->get()->toArray();
    }
}