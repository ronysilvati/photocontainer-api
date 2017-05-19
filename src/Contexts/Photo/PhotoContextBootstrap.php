<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo;

use PhotoContainer\PhotoContainer\Contexts\Photo\Action\CreatePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\DeletePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\DislikePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\DownloadPhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\LikePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Like;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Persistence\EloquentPhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Persistence\FilesystemPhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Stream;

class PhotoContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->post('/photo', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $data = $request->getParsedBody();

                $action = new CreatePhoto(new EloquentPhotoRepository($container['DatabaseProvider']), new FilesystemPhotoRepository());

                $event_id = (int) $data['event_id'];

                $allPhotos = [];
                foreach ($_FILES as $photo) {
                    $allPhotos[] = new Photo(null, $event_id, $photo, $photo['tmp_name']);
                }

                $actionResponse = $action->handle($allPhotos, $event_id);

                return $response->withJson($actionResponse->jsonSerialize(), $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/photo/{photo_id}/user/{user_id}/download', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $action = new DownloadPhoto(new EloquentPhotoRepository($container['DatabaseProvider']));
                $actionResponse = $action->handle((int) $args['photo_id'], (int) $args['user_id']);

                if (get_class($actionResponse) == DomainExceptionResponse::class) {
                    return $response->withJson($actionResponse->jsonSerialize(), $actionResponse->getHttpStatus());
                }

                $stream = new Stream($actionResponse->getFileToStream()); // create a stream instance for the response body
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

        $slimApp->app->post('/photo/{photo_id}/like/publisher/{publisher_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $like = new Like($args['publisher_id'], $args['photo_id']);

            $action = new LikePhoto(new EloquentPhotoRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($like);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->delete('/photo/{photo_id}/dislike/publisher/{publisher_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $like = new Like($args['publisher_id'], $args['photo_id']);

            $action = new DislikePhoto(new EloquentPhotoRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($like);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->delete('/photo/{guid}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new DeletePhoto(new EloquentPhotoRepository($container['DatabaseProvider']), new FilesystemPhotoRepository());
            $actionResponse = $action->handle($args['guid']);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        return $slimApp;
    }
}
