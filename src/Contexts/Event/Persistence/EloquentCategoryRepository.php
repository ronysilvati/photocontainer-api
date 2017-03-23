<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\CategoryRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Category as CategoryModel;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Category;

class EloquentCategoryRepository implements CategoryRepository
{
    public function findAll(): array
    {
        $all = CategoryModel::all();

        return $all->map(function ($item, $key) {
            return new Category($item->id, $item->description);
        })->toArray();
    }
}