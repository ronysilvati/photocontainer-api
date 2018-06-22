<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Response;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;

class UserResponse implements \JsonSerializable
{
    private $selfReference;
    private $addressReference;
    private $detailReference;
    private $httpStatus = 200;
    private $user;
    private $profileImageUri;

    public function __construct(User $user, ?string $profileImageUri = null)
    {
        $this->user = $user;
        $this->selfReference = "users/{$this->user->getId()}";

        if ($user->getDetails()) {
            $this->detailReference = "details/{$user->getDetails()->getId()}";
        }

        if ($user->getAddress() && $user->getAddress()->getId() > 0) {
            $this->addressReference = "details/{$user->getAddress()->getId()}";
        }

        $this->profileImageUri = $profileImageUri;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    public function jsonSerialize()
    {
        $out = [
            'id' => $this->user->getId(),
            'name' => $this->user->getName(),
            'email' => $this->user->getEmail(),
            '_links' => [
                '_self' => ['href' => $this->selfReference],
            ],
            'profile' => ['profile_id' => $this->user->getProfile()->getProfileId()],
        ];

        if ($this->profileImageUri) {
            $out['profile_image'] = $this->profileImageUri;
        }

        if ($this->detailReference) {
            $out['_links']['details'] = ['href' => $this->detailReference];
            $out['details'] = [
                'blog' => $this->user->getDetails()->getBlog(),
                'facebook' => $this->user->getDetails()->getFacebook(),
                'pinterest' => $this->user->getDetails()->getPinterest(),
                'site' => $this->user->getDetails()->getSite(),
                'instagram' => $this->user->getDetails()->getInstagram(),
                'phone' => $this->user->getDetails()->getPhone(),
                'birth' => $this->user->getDetails()->getBirth(),
            ];

            $photographerDetails = $this->user->getDetails()->getPhographerDetails();
            if ($photographerDetails) {
                $out['details']['name_type'] = $this->user->getDetails()->getPhographerDetails()->getNameType();
                $out['details']['studio'] = $this->user->getDetails()->getPhographerDetails()->getStudio();
                $out['details']['bio'] = $this->user->getDetails()->getPhographerDetails()->getBio();
            }
        }

        if ($this->addressReference) {
            $out['_links']['address'] = ['href' => $this->addressReference];
            $out['address'] = [
                'zipcode' => $this->user->getAddress()->getZipcode(),
                'country' => $this->user->getAddress()->getCountry(),
                'state' => $this->user->getAddress()->getState(),
                'city' => $this->user->getAddress()->getCity(),
                'neighborhood' => $this->user->getAddress()->getNeighborhood(),
                'street' => $this->user->getAddress()->getStreet(),
                'complement' => $this->user->getAddress()->getComplement(),
            ];
        }

        return $out;
    }
}
