<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Command\DownloadSelectedCommand;
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
     * DownloadSelected constructor.
     * @param PhotoRepository $dbRepo
     * @param ZipCreatorHelper $zipCreatorHelper
     */
    public function __construct(
        PhotoRepository $dbRepo,
        ZipCreatorHelper $zipCreatorHelper
    ) {
        $this->dbRepo = $dbRepo;
        $this->zipCreatorHelper = $zipCreatorHelper;
    }

    /**
     * @param DownloadSelectedCommand $command
     * @return DownloadSelectedResponse|NoContentResponse
     * @throws DomainViolationException
     */
    public function handle(DownloadSelectedCommand $command)
    {
        $selected = null;
        switch ($command->getType()) {
            case 'all':
                $event_id = (int) $command->getIds();
                $selected = $this->dbRepo->selectAllPhotos($event_id, $command->getPublisherId());
                break;

            case 'select':
                $photo_ids = array_map('intval', explode(',', $command->getIds()));
                $selected = $this->dbRepo->selectPhotos($photo_ids, $command->getPublisherId());
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
            $download = new Download(null, $command->getPublisherId(), $currentPhoto);
            $this->dbRepo->download($download);
        }

        //Limpar os eventos gerados no loop acima - deve ser enviado apenas um Email
        EventRecorder::getInstance()->pullEvents();

        $selected->attachZip($zipname);

        return new DownloadSelectedResponse($selected);
    }
}