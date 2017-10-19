<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;


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
     * @param int $user_id
     * @param array $file
     * @return ProfileImageUploaded
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     * @throws DomainViolationException
     * @throws \Exception
     */
    public function handle(int $user_id, array $file): \PhotoContainer\PhotoContainer\Contexts\User\Response\ProfileImageUploaded
    {
        $user = $this->userRepo->findUser($user_id);

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

        $this->profileImageHelper->removeOldVersions($user_id);

        $result = $this->imageHelper->createImage(
            $file['tmp_name'],
            $this->profileImageHelper->generateName($user_id, $file)
        );

        if (!$result) {
            throw new DomainViolationException($this->imageHelper->getErrMessage());
        }

        return new ProfileImageUploaded($this->profileImageHelper->resolveUri($user_id));
    }
}