<?php
/**
 * Created by PhpStorm.
 * User: marte
 * Date: 18/03/2017
 * Time: 17:38
 */

namespace PhotoContainer\PhotoContainer\Contexts\User\Response;

use PhotoContainer\PhotoContainer\Infrastructure\Entity;

class UserResponse implements \JsonSerializable
{
    private $selfReference;
    private $detailReference;
    private $httpStatus = 200;
    private $user;

    public function __construct(Entity $user)
    {
        $this->user = $user;
        $this->selfReference = "users/{$this->user->getId()}";

        if ($user->getDetails()) {
            $this->detailReference = "details/{$user->getDetails()->getId()}";
        }
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    function jsonSerialize(): array
    {
        $out = [
            "id" => $this->user->getId(),
            "name" => $this->user->getName(),
            "email" => $this->user->getEmail(),
            "_links" => [
                "_self" => ['href' => $this->selfReference],
            ],
            'profile' => ['profile_id' => $this->user->getProfile()->getProfileId()],
        ];

        if ($this->detailReference) {
            $out['_links']['details'] = ['href' => $this->detailReference];
            $out['details'] = [
                'blog' => $this->user->getDetails()->getBlog(),
                'facebook' => $this->user->getDetails()->getFacebook(),
                'linkedin' => $this->user->getDetails()->getLinkedin(),
                'site' => $this->user->getDetails()->getSite(),
                'instagram' => $this->user->getDetails()->getInstagram(),
                'phone' => $this->user->getDetails()->getPhone(),
                'gender' => $this->user->getDetails()->getGender(),
            ];
        }

        return $out;
    }
}