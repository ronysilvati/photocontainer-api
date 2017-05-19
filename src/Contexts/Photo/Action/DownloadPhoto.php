<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Email\DownloadedPhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DownloadResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventGeneratorTrait;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DownloadPhoto
{
    use EventGeneratorTrait;

    /**
     * @var PhotoRepository
     */
    private $dbRepo;

    /**
     * DownloadPhoto constructor.
     * @param PhotoRepository $dbRepo
     */
    public function __construct(PhotoRepository $dbRepo)
    {
        $this->dbRepo = $dbRepo;
    }

    /**
     * @param int $photo_id
     * @param int $publisher_id
     * @return DownloadResponse|DomainExceptionResponse
     */
    public function handle(int $photo_id, int $publisher_id)
    {
        $photo = $this->dbRepo->find($photo_id);
        $download = new Download(null, $publisher_id, $photo);

        $this->dbRepo->download($download);

        $this->sendEmailToPhotographer($photo, $publisher_id);

        return new DownloadResponse($download);
    }

    /**
     * @param Photo $photo
     * @param int $publisher_id
     */
    public function sendEmailToPhotographer(Photo $photo, int $publisher_id)
    {
        $photographer = $this->dbRepo->findPhotoOwner($photo);
        $publisher = $this->dbRepo->findPublisher($publisher_id);

        $data = [
            '{TAG}' => 'TESTE DE TAG',
            '{PUBLISHER}' => $publisher->getName()
        ];

        $email = new DownloadedPhoto(
            $data,
            ['name' => $photographer->getName(), 'email' => $photographer->getEmail()]
        );

        $this->addEvent('generic.sendmail', $email);
    }
}
