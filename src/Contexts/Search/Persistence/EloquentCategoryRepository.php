<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Category;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\CategoryRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Category as CategoryModel;

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