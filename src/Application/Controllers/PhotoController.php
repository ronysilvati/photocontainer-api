<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Photo\Action\CreatePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\DeletePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\DislikePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\DownloadSelected;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\DownloadPhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\LikePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\SetPhotoAsCover;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Like;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Infrastructure\NoContentResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Stream;
use Slim\Http\UploadedFile;

class PhotoController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param CreatePhoto $action
     * @return mixed
     * @throws \RuntimeException
     */
    public function createPhoto(
        ServerRequestInterface $request,
        ResponseInterface $response,
        CreatePhoto $action
    ) {
        $data = $request->getParsedBody();

        $event_id = (int) $data['event_id'];

        $allPhotos = [];
        $uploaded = $request->getUploadedFiles();
        foreach ($uploaded as $files) {
            /** @var UploadedFile $file */
            foreach ($files as $file) {
                if ($file->getError() !== UPLOAD_ERR_OK) {
                    throw new \RuntimeException('Erro no envio do arquivo.');
                }

                $filedata = [
                    'error' => $file->getError(),
                    'name' => $file->getClientFilename(),
                    'size' => $file->getSize(),
                    'tmp_name' => $file->file,
                    'type' => $file->getClientMediaType(),
                ];

                $allPhotos[] = new Photo(null, $event_id, $filedata, $file->getClientFilename());
            }
        }

        $actionResponse = $action->handle($allPhotos, $event_id);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param DownloadPhoto $action
     * @param int $photo_id
     * @param int $user_id
     * @return ResponseInterface
     */
    public function downloadPhoto(
        ServerRequestInterface $request,
        ResponseInterface $response,
        DownloadPhoto $action,
        int $photo_id,
        int $user_id
    ): \Psr\Http\Message\ResponseInterface
    {
        $actionResponse = $action->handle($photo_id, $user_id);

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
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param LikePhoto $action
     * @param int $photo_id
     * @param int $publisher_id
     * @return mixed
     */
    public function like(
        ServerRequestInterface $request,
        ResponseInterface $response,
        LikePhoto $action,
        int $photo_id,
        int $publisher_id
    ) {
        $like = new Like($publisher_id, $photo_id);

        $actionResponse = $action->handle($like);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param DislikePhoto $action
     * @param int $photo_id
     * @param int $publisher_id
     * @return mixed
     */
    public function dislike(
        ServerRequestInterface $request,
        ResponseInterface $response,
        DislikePhoto $action,
        int $photo_id,
        int $publisher_id
    ) {
        $like = new Like($publisher_id, $photo_id);
        $actionResponse = $action->handle($like);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param DeletePhoto $action
     * @param string $guid
     * @return mixed
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function delete(
        ServerRequestInterface $request,
        ResponseInterface $response,
        DeletePhoto $action,
        string $guid
    ) {
        $actionResponse = $action->handle($guid);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param SetPhotoAsCover $action
     * @param string $guid
     * @return mixed
     */
    public function asCover(
        ServerRequestInterface $request,
        ResponseInterface $response,
        SetPhotoAsCover $action,
        string $guid
    ) {
        $actionResponse = $action->handle($guid);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param DownloadSelected $action
     * @param string $type
     * @param string $ids
     * @param int $publisher_id
     * @return ResponseInterface
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     */
    public function downloadSelectedPhotos(
        ServerRequestInterface $request,
        ResponseInterface $response,
        DownloadSelected $action,
        string $type,
        string $ids,
        int $publisher_id
    ): \Psr\Http\Message\ResponseInterface
    {
        $actionResponse = $action->handle($type, $publisher_id, $ids);

        if (in_array(get_class($actionResponse), [DomainExceptionResponse::class, NoContentResponse::class], true)) {
            return $response->withJson($actionResponse->jsonSerialize(), $actionResponse->getHttpStatus());
        }

        $filename = explode('/', $actionResponse->getSelectedPhotos()->getZip());

        $stream = new Stream($actionResponse->getFileToStream());
        return $response->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="'.end($filename). '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);
    }
}