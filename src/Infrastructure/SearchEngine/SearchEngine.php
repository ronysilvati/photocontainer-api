<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\SearchEngine;

use Doctrine\ORM\EntityRepository;

class SearchEngine extends EntityRepository
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
     * @param string $resource
     * @return SearchEngine
     */
    public function addResource(string $resource): SearchEngine
    {
        $this->getEntityManager()->createQueryBuilder();

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