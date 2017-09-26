<?php
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnPublisherCreated;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnUserCreated;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailPhotographerOnDownload;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailPublisherOnDownload;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnDownloadRequest;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnRequestResponse;
use PhotoContainer\PhotoContainer\Application\EventListeners\SendEmailOnPublisherPublication;

/** @var \League\Event\Emitter $eventEmitter */
$eventDispatcher = $container->get('EventDispatcher');

$eventDispatcher->addListener('user_created', $container->get(SendEmailOnUserCreated::class));

$eventDispatcher->addListener('publisher_registered', $container->get(SendEmailOnPublisherCreated::class));

$eventDispatcher->addListener('downloaded_photo', $container->get(SendEmailPhotographerOnDownload::class));
$eventDispatcher->addListener('downloaded_photo', $container->get(SendEmailPublisherOnDownload::class));

$eventDispatcher->addListener('download_requested', $container->get(SendEmailOnDownloadRequest::class));
$eventDispatcher->addListener('download_request_response', $container->get(SendEmailOnRequestResponse::class));

$eventDispatcher->addListener('publisher_published', $container->get(SendEmailOnPublisherPublication::class));