<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Command\UploadProfileImageCommand;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\ProfileImageUploaded;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\ImageHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\ProfileImageHelper;

class UploadProfileImage
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var ProfileImageHelper
     */
    private $profileImageHelper;

    // Largura máxima da imagem.
    const MAX_WIDTH = 1200;

    //Altura máxima da imagem.
    const MAX_HEIGHT = 300;

    /**
     * UploadProfileImage constructor.
     * @param UserRepository $userRepo
     * @param ImageHelper $imageHelper
     * @param ProfileImageHelper $profileImageHelper
     */
    public function __construct(UserRepository $userRepo, ImageHelper $imageHelper, ProfileImageHelper $profileImageHelper)
    {
        $this->userRepo = $userRepo;
        $this->imageHelper = $imageHelper;
        $this->profileImageHelper = $profileImageHelper;
    }

    /**
     * @param UploadProfileImageCommand $command
     * @return ProfileImageUploaded
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     * @throws DomainViolationException
     * @throws \Exception
     */
    public function handle(UploadProfileImageCommand $command): ProfileImageUploaded
    {
        $user = $this->userRepo->findUser($command->getUserId());

        if (!$user) {
            throw new DomainViolationException('Usuário não encontrado');
        }

        $this->imageHelper->configure(getenv('PROFILE_IMAGE_PATH'));

        $this->imageHelper->addCriteriaForSaving(
            ImageHelper::CRITERIA_DIMENSIONS,
            [
                'width' => self::MAX_WIDTH,
                'height' => self::MAX_HEIGHT,
                'errMsg' => 'A imagem está fora das dimensões esperadas: '.self::MAX_WIDTH.' x '.self::MAX_HEIGHT.'.'
            ]
        );

        $this->imageHelper->addCriteriaForSaving(
            ImageHelper::CRITERIA_MIMETYPE,
            [
                'mimetypes' => ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'],
                'errMsg' => 'Formato de arquivo não permitido'
            ]
        );

        $this->profileImageHelper->removeOldVersions($command->getUserId());

        $result = $this->imageHelper->createImage(
            $command->getFile()->getStream(),
            $this->profileImageHelper->generateName($command->getUserId(), $command->getFile()->getClientMediaType())
        );

        if (!$result) {
            throw new DomainViolationException($this->imageHelper->getErrMessage());
        }

        return new ProfileImageUploaded($this->profileImageHelper->resolveUri($command->getUserId()));
    }
}