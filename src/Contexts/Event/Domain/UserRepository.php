<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface UserRepository
{
    /**
     * @param Photographer $photographer
     * @return mixed
     */
    public function findPhotographer(Photographer $photographer);

    /**
     * @param Publisher $publisher
     * @return mixed
     */
    public function findPublisher(Publisher $publisher);

    /**
     * @param int $profile
     * @return mixed
     */
    public function findByProfile(int $profile);
}