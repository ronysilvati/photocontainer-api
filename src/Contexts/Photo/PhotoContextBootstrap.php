<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo;

use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentTagRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\CreatePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\DownloadPhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Persistence\EloquentPhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Persistence\FilesystemPhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\ImageHelper;
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

                return $response->withJson($actionResponse->jsonSerialize(), $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/photo/{photo_id}/user/{user_id}/download', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $data = $request->getParsedBody();

                $action = new DownloadPhoto(new EloquentPhotoRepository());
                $actionResponse = $action->handle((int) $args['photo_id'], (int) $args['user_id']);

                $stream = new \Slim\Http\Stream($actionResponse->getFileToStream()); // create a stream instance for the response body
                return $response->withHeader('Content-Type', 'application/force-download')
                    ->withHeader('Content-Type', 'application/octet-stream')
                    ->withHeader('Content-Type', 'application/download')
                    ->withHeader('Content-Description', 'File Transfer')
                    ->withHeader('Content-Transfer-Encoding', 'binary')
                    ->withHeader('Content-Disposition', 'attachment; filename="' . $actionResponse->getDownload()->getPhoto()->getPhysicalName() . '"')
                    ->withHeader('Expires', '0')
                    ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                    ->withHeader('Pragma', 'public')
                    ->withBody($stream); // all stream contents will be sent to the response
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        return $slimApp;
    }

}