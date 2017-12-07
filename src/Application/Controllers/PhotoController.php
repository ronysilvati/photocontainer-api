<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Photo\Command\CreatePhotoCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Command\DeletePhotoCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Command\DislikePhotoCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Command\DownloadPhotoCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Command\DownloadSelectedCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Command\LikePhotoCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Command\SetPhotoAsCoverCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DownloadSelectedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Stream;

class PhotoController extends Controller
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createPhoto(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        $uploaded = $request->getUploadedFiles();

        $actionResponse = $this->commandBus()->handle(new CreatePhotoCommand($data, $uploaded));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $photoId
     * @param int $userId
     * @return ResponseInterface
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function downloadPhoto(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $photoId,
        int $userId
    ): ResponseInterface
    {
        $actionResponse = $this->commandBus()->handle(new DownloadPhotoCommand($photoId, $userId));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $photoId
     * @param int $publisherId
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function like(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $photoId,
        int $publisherId
    ) {
        $actionResponse = $this->commandBus()->handle(new LikePhotoCommand($photoId, $publisherId));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $photoId
     * @param int $publisherId
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dislike(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $photoId,
        int $publisherId
    ) {
        $actionResponse = $this->commandBus()->handle(new DislikePhotoCommand($photoId, $publisherId));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $guid
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function delete(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $guid
    ) {
        $actionResponse = $this->commandBus()->handle(new DeletePhotoCommand($guid));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $guid
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function asCover(ServerRequestInterface $request, ResponseInterface $response, string $guid)
    {
        $actionResponse = $this->commandBus()->handle(new SetPhotoAsCoverCommand($guid));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $type
     * @param string $ids
     * @param int $publisherId
     * @return ResponseInterface
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function downloadSelectedPhotos(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $type,
        string $ids,
        int $publisherId
    ): ResponseInterface
    {
        $actionResponse = $this->commandBus()->handle(new DownloadSelectedCommand($type, $publisherId, $ids));

        if (\get_class($actionResponse) !== DownloadSelectedResponse::class) {
            return $response->withJson($actionResponse->jsonSerialize(), $actionResponse->getHttpStatus());
        }

        $filename = explode('/', $actionResponse->getSelectedPhotos()->getZip());

        return $this->streamFileResponse($actionResponse->getFileToStream(), end($filename), $response);
    }

    /**
     * @param $stream
     * @param string $filename
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function streamFileResponse($stream, string $filename, ResponseInterface $response): ResponseInterface
    {
        $stream = new Stream($stream); // create a stream instance for the response body
        return $response->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="'.$filename.'"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);
    }
}