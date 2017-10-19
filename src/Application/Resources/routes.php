<?php

use PhotoContainer\PhotoContainer\Application\Controllers\CepController;
use PhotoContainer\PhotoContainer\Application\Controllers\SearchController;
use PhotoContainer\PhotoContainer\Application\Controllers\ApprovalController;
use PhotoContainer\PhotoContainer\Application\Controllers\ContactController;
use PhotoContainer\PhotoContainer\Application\Controllers\AuthController;
use PhotoContainer\PhotoContainer\Application\Controllers\EventController;
use PhotoContainer\PhotoContainer\Application\Controllers\UserController;
use PhotoContainer\PhotoContainer\Application\Controllers\PhotoController;

//$app->post('/login', [AuthController::class, 'login']);
//
//$app->get('/location/countries', [CepController::class, 'getCountries']);
//$app->get('/location/zipcode/{cep}', [CepController::class, 'getCep']);
//$app->get('/location/country/{country_id}/states', [CepController::class, 'getStates']);
//$app->get('/location/state/{state_id}/cities', [CepController::class, 'getCities']);

$app->get('/search/events', [SearchController::class, 'searchEvent']);
$app->get('/search/categories', [SearchController::class, 'searchCategories']);
$app->get('/search/tags', [SearchController::class, 'searchTags']);
$app->get('/search/events/{event_id}/photos/user/{user_id}', [SearchController::class, 'searchEventPhotosPublisher']);
$app->get('/search/events/{photographer_id}/photos', [SearchController::class, 'searchEventPhotosPhotographer']);
$app->get('/search/photo/user/{publisher_id}/{type:downloads|favorites}', [SearchController::class, 'publisherHistoric']);
$app->get('/search/waiting_approval/user/{photographer_id}', [SearchController::class, 'waitingForApproval']);
$app->get('/search/notifications/user/{user_id}', [SearchController::class, 'notifications']);

//$app->post('/events/{event_id}/request/user/{publisher_id}', [ApprovalController::class, 'requestDownload']);
//$app->put('/events/{event_id}/approval/user/{publisher_id}', [ApprovalController::class, 'approvalDownload']);
//$app->put('/events/{event_id}/disapproval/user/{publisher_id}', [ApprovalController::class, 'disapprovalDownload']);
//
//$app->post('/contact', [ContactController::class, 'createContact']);
//$app->get('/contact/total', [ContactController::class, 'total']);
//$app->get('/contact/list', [ContactController::class, 'list']);
//
//$app->get('/events', [EventController::class, 'findEvent']);
//$app->post('/events', [EventController::class, 'createEvent']);
//$app->delete('/events/{id}', [EventController::class, 'deleteEvent']);
//$app->put('/events/{id}', [EventController::class, 'editEvent']);
//$app->post('/events/{event_id}/favorite/publisher/{publisher_id}', [EventController::class, 'createFavorite']);
//$app->delete('/events/{event_id}/favorite/publisher/{publisher_id}', [EventController::class, 'deleteFavorite']);
//$app->post('/events/{id}/tags', [EventController::class, 'updateTags']);
//$app->post('/events/{id}/suppliers', [EventController::class, 'updateSuppliers']);
//$app->post('/events/{event_id}/broadcastPublishers', [EventController::class, 'broadcastNewEvent']);
//$app->post('/events/publisherPublish', [EventController::class, 'publisherPublish']);
//
//$app->post('/users', [UserController::class, 'createUser']);
//$app->get('/users', [UserController::class, 'findUser']);
//$app->get('/users/satisfyPreConditions', [UserController::class, 'findFreeSlotForUser']);
//$app->patch('/users/{id}', [UserController::class, 'updateUser']);
//$app->post('/users/{id}/profileImage', [UserController::class, 'createProfileImage']);
//$app->post('/users/requestPasswordChange', [UserController::class, 'requestPwdChange']);
//$app->post('/users/updatePassword', [UserController::class, 'updatePassword']);
//
//$app->post('/photo', [PhotoController::class, 'createPhoto']);
//$app->get('/photo/{photo_id}/user/{user_id}/download', [PhotoController::class, 'downloadPhoto']);
//$app->post('/photo/{photo_id}/like/publisher/{publisher_id}', [PhotoController::class, 'like']);
//$app->delete('/photo/{photo_id}/dislike/publisher/{publisher_id}', [PhotoController::class, 'dislike']);
//$app->delete('/photo/{guid}', [PhotoController::class, 'delete']);
//$app->patch('/photo/cover/{guid}', [PhotoController::class, 'asCover']);
//$app->get(
//    '/event/download/{type:all|select}/{ids}/publisher/{publisher_id}',
//    [PhotoController::class, 'downloadSelectedPhotos']
//);