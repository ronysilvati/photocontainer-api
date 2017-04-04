<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo;

use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentTagRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\CreatePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Persistence\EloquentPhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Persistence\FilesystemPhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PhotoContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->post('/photo', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $data = $request->getParsedBody();

                $action = new CreatePhoto(new EloquentPhotoRepository(), new FilesystemPhotoRepository());

                $event_id = (int) $data['event_id'];

                $allPhotos = [];
                foreach ($_FILES as $photo) {
                    $allPhotos[] = new Photo(null, $event_id, $photo, $photo['tmp_name']);
                }

                $actionResponse = $action->handle($allPhotos);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        return $slimApp;

    }

}