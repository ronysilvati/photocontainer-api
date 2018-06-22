<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;

use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DownloadSelectedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventRecorder;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\ZipCreatorHelper;
use PhotoContainer\PhotoContainer\Infrastructure\NoContentResponse;

class DownloadSelected
{
    /**
     * @var PhotoRepository
     */
    private $dbRepo;

    /**
     * @var ZipCreatorHelper
     */
    private $zipCreatorHelper;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * DownloadSelected constructor.
     * @param PhotoRepository $dbRepo
     * @param EventRepository $eventRepository
     * @param ZipCreatorHelper $zipCreatorHelper
     */
    public function __construct(
        PhotoRepository $dbRepo,
        EventRepository $eventRepository,
        ZipCreatorHelper $zipCreatorHelper
    ) {
        $this->dbRepo = $dbRepo;
        $this->zipCreatorHelper = $zipCreatorHelper;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param string $type
     * @param int $publisher_id
     * @param string $ids
     * @return DownloadSelectedResponse|NoContentResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     * @throws DomainViolationException
     */
    public function handle(string $type, int $publisher_id, string $ids)
    {
        $selected = null;
        switch ($type) {
            case 'all':
                $event_id = (int) $ids;
                $selected = $this->dbRepo->selectAllPhotos($event_id, $publisher_id);
                break;

            case 'select':
                $photo_ids = array_map('intval', explode(',', $ids));
                $selected = $this->dbRepo->selectPhotos($photo_ids, $publisher_id);
                break;
        }

        if (!$selected) {
            return new NoContentResponse();
        }

        $filesForZip = [];
        /** @var Photo $currentPhoto */
        foreach ($selected->getPhotos() as $currentPhoto) {
            $filesForZip[] = $currentPhoto->getFilePath('protected', true, true);
        }

        $zipname = getenv('ZIP_PATH').'/event_'.time().'.zip';
        if (!$this->zipCreatorHelper->createFromFiles($zipname, $filesForZip)) {
            throw new DomainViolationException('Falha no envio das fotos.');
        }

        foreach ($selected->getPhotos() as $currentPhoto) {
            $download = new Download(null, $publisher_id, $currentPhoto);
            $this->dbRepo->download($download);
        }

        //Limpar os eventos gerados no loop acima - deve ser enviado apenas um Email
        EventRecorder::getInstance()->pullEvents();

        $selected->attachZip($zipname);

        return new DownloadSelectedResponse($selected);
    }
}