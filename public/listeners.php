<?php
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnPublisherCreated;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnUserCreated;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailPhotographerOnDownload;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailPublisherOnDownload;
use PhotoContainer\PhotoContainer\Application\EventListeners\GenerateDownloadLog;

/** @var \League\Event\Emitter $eventEmitter */
$eventEmitter = $app->getContainer()->get(\League\Event\Emitter::class);

$eventEmitter->addListener('user_created', $app->getContainer()->get(SendEmailOnUserCreated::class));

$eventEmitter->addListener('publisher_registered', $app->getContainer()->get(SendEmailOnPublisherCreated::class));

$eventEmitter->addListener('downloaded_photo', $app->getContainer()->get(SendEmailPhotographerOnDownload::class));
$eventEmitter->addListener('downloaded_photo', $app->getContainer()->get(SendEmailPublisherOnDownload::class));
