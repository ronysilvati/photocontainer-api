<?php
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnPublisherCreated;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnUserCreated;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailPhotographerOnDownload;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailPublisherOnDownload;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnDownloadRequest;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnRequestResponse;

$container = $app->getContainer();

/** @var \League\Event\Emitter $eventEmitter */
$eventEmitter = $container->get(\League\Event\Emitter::class);

$eventEmitter->addListener('user_created', $container->get(SendEmailOnUserCreated::class));

$eventEmitter->addListener('publisher_registered', $container->get(SendEmailOnPublisherCreated::class));

$eventEmitter->addListener('downloaded_photo', $container->get(SendEmailPhotographerOnDownload::class));
$eventEmitter->addListener('downloaded_photo', $container->get(SendEmailPublisherOnDownload::class));

$eventEmitter->addListener('download_requested', $container->get(SendEmailOnDownloadRequest::class));
$eventEmitter->addListener('download_request_response', $container->get(SendEmailOnRequestResponse::class));
