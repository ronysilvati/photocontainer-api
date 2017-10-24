<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\Address;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\PhotographerDetails;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod;


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

    /**
     * UpdateUser constructor.
     * @param UserRepository $userRepository
     * @param CryptoMethod $cryptoMethod
     */
    public function __construct(UserRepository $userRepository, CryptoMethod $cryptoMethod)
    {
        $this->userRepository = $userRepository;
        $this->cryptoMethod = $cryptoMethod;
    }

    /**
     * @param int $id
     * @param array $data
     * @return UserResponse
     * @throws \Exception
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     */
    public function handle(int $id, array $data): UserResponse
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
            $address = $this->updateAddress($user, $data['address']);
            $user->changeAddress($address);
        }

        if (isset($data['details'])) {
            $user->changeDetails($this->updateDetails($user->getDetails(), $data['details']));
        }

        if (isset($data['profile_id']) && $data['profile_id'] == 2) {
            $photographerDetails = new PhotographerDetails(
                $data['details']['bio'] ?? '',
                $data['details']['studio'] ?? '',
                $data['details']['name_type'] ?? ''
            );

            $user->getDetails()->changePhographerDetails($photographerDetails);
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $user->changePwd($this->cryptoMethod->hash($data['password']));
        }

        $user = $this->userRepository->updateUser($user);
        return new UserResponse($user);
    }

    /**
     * @param User $user
     * @param array $dataAddress
     * @return Address
     * @throws \Exception
     */
    private function updateAddress(User $user, array $dataAddress): Address
    {
        $address = $user->getAddress() ?? new Address();

        $address->changeCountry($dataAddress['country']);
        $address->changeZipcode($dataAddress['zipcode']);
        $address->changeState($dataAddress['state']);
        $address->changeCity($dataAddress['city']);
        $address->changeNeighborhood($dataAddress['neighborhood']);
        $address->changeStreet($dataAddress['street']);
        $address->changeComplement($dataAddress['complement']);

        return $address;
    }

    /**
     * @param Details $details
     * @param array $dataDetails
     * @return Details
     */
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
