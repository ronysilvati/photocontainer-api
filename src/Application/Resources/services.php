<?php

use \Psr\Container\ContainerInterface;

$services = [];

$services[\PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CityRepository::class] = function (ContainerInterface $container) {
    return $container
        ->get(\Doctrine\ORM\EntityManager::class)
        ->getRepository(\PhotoContainer\PhotoContainer\Contexts\Cep\Domain\City::class);
};

$services[\PhotoContainer\PhotoContainer\Contexts\Cep\Domain\StateRepository::class] = function (ContainerInterface $container) {
    return $container
        ->get(\Doctrine\ORM\EntityManager::class)
        ->getRepository(\PhotoContainer\PhotoContainer\Contexts\Cep\Domain\State::class);
};

$services[\PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CountryRepository::class] = function (ContainerInterface $container) {
    return $container
        ->get(\Doctrine\ORM\EntityManager::class)
        ->getRepository(\PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Country::class);
};

$services[\PhotoContainer\PhotoContainer\Contexts\Auth\Domain\AuthRepository::class] = function (ContainerInterface $container) {
    return $container
        ->get(\Doctrine\ORM\EntityManager::class)
        ->getRepository(\PhotoContainer\PhotoContainer\Contexts\Auth\Domain\Auth::class);
};

$services[\PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository::class] = function (ContainerInterface $container) {
    return $container
        ->get(\Doctrine\ORM\EntityManager::class)
        ->getRepository(\PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest::class);
};

//$services['PhotoContainer\PhotoContainer\Contexts\*\Domain\*Repository'] = DI\object(
//    'PhotoContainer\PhotoContainer\Contexts\*\Persistence\Eloquent*Repository'
//);

return $services;