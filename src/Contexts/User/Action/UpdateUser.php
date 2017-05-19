<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\Address;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\PhotographerDetails;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class UpdateUser
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var CryptoMethod
     */
    private $cryptoMethod;

    public function __construct(UserRepository $userRepository, CryptoMethod $cryptoMethod)
    {
        $this->userRepository = $userRepository;
        $this->cryptoMethod = $cryptoMethod;
    }

    public function handle(int $id, array $data)
    {
        /** @var User $user */
        $user = $this->userRepository->findUser($id);

        if (isset($data['name'])) {
            $user->changeName($data['name']);
        }

        if (isset($data['email'])) {
            $user->changeEmail($data['email']);
        }

        if (isset($data['details']['blog'])) {
            $user->changeBlog($data['details']['blog']);
        }

        if (isset($data['address'])) {
            $address = $this->updateAddress($user->getAddress(), $data['address']);
            $user->changeAddress($address);
        }

        if ($data['profile_id'] == 2) {
            $photographerDetails = new PhotographerDetails(
                isset($data['details']['bio']) ? $data['details']['bio'] : '',
                isset($data['details']['studio']) ? $data['details']['studio'] : '',
                isset($data['details']['name_type']) ? $data['details']['name_type'] : ''
            );

            $user->getDetails()->changePhographerDetails($photographerDetails);
        }

        $details = $this->updateDetails($user->getDetails(), $data['details']);
        $user->changeDetails($details);

        $crypto = null;
        if (isset($data['password'])) {
            $crypto = empty($data['password']) ? '' : $this->cryptoMethod->hash($data['password']);
        }

        $user = $this->userRepository->updateUser($user, $crypto);
        return new UserResponse($user);
    }

    private function updateAddress(Address $address, array $dataAddress): Address
    {
        $address->changeCountry($dataAddress['country']);
        $address->changeZipcode($dataAddress['zipcode']);
        $address->changeState($dataAddress['state']);
        $address->changeCity($dataAddress['city']);
        $address->changeNeighborhood($dataAddress['neighborhood']);
        $address->changeStreet($dataAddress['street']);
        $address->changeComplement($dataAddress['complement']);

        return $address;
    }

    private function updateDetails(Details $details, array $dataDetails): Details
    {
        if (isset($dataDetails['facebook'])) {
            $details->changeFacebook($dataDetails['facebook']);
        }

        if (isset($dataDetails['pinterest'])) {
            $details->changePinterest($dataDetails['pinterest']);
        }

        if (isset($dataDetails['instagram'])) {
            $details->changeInstagram($dataDetails['instagram']);
        }

        if (isset($dataDetails['phone'])) {
            $details->changePhone($dataDetails['phone']);
        }

        if (isset($dataDetails['birth'])) {
            $details->changeBirth($dataDetails['birth']);
        }

        if (isset($dataDetails['site'])) {
            $details->changeSite($dataDetails['site']);
        }

        return $details;
    }
}
