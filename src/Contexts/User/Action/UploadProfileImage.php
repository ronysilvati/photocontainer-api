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

    public function handle(int $user_id, array $file)
    {
        $user = $this->userRepo->findUser($user_id);

        if (!$user) {
            throw new DomainViolationException('Usuário não encontrado');
        }

        $this->imageHelper->addCriteriaForSaving(
            ImageHelper::CRITERIA_DIMENSIONS,
            ['width' => 1153, 'height' => 300, 'errMsg' => 'A imagem está fora das dimensões especificadas. Esperado: 1153 x 300.']
        );

        $this->imageHelper->addCriteriaForSaving(
            ImageHelper::CRITERIA_MIMETYPE,
            [
                'mimetypes' => ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'],
                'errMsg' => 'Formato de arquivo não permitido'
            ]
        );

        $name = $this->profileImageHelper->generateName($user_id, $file);

        $this->profileImageHelper->removeOldVersions($user_id);

        $result = $this->imageHelper->createImage($file['tmp_name'], $name);

        if (!$result) {
            throw new DomainViolationException($this->imageHelper->getErrMessage());
        }

        return new ProfileImageUploaded($this->profileImageHelper->resolveUri($user_id));
    }
}