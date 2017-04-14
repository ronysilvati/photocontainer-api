<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\PhotographerDetails;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class UpdateUser
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(int $id, array $data, ?string $crypto)
    {
        try {
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
                $address = $user->getAddress();

                $address->changeCountry($data['address']['country']);
                $address->changeZipcode($data['address']['zipcode']);
                $address->changeState($data['address']['state']);
                $address->changeCity($data['address']['city']);
                $address->changeNeighborhood($data['address']['neighborhood']);
                $address->changeStreet($data['address']['street']);
                $address->changeComplement($data['address']['complement']);

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

            if (isset($data['details']['facebook'])) {
                $user->getDetails()->changeFacebook($data['details']['facebook']);
            }

            if (isset($data['details']['pinterest'])) {
                $user->getDetails()->changePinterest($data['details']['pinterest']);
            }

            if (isset($data['details']['instagram'])) {
                $user->getDetails()->changeInstagram($data['details']['instagram']);
            }

            if (isset($data['details']['phone'])) {
                $user->getDetails()->changePhone($data['details']['phone']);
            }

            if (isset($data['details']['birth'])) {
                $user->getDetails()->changeBirth($data['details']['birth']);
            }

            if (isset($data['details']['site'])) {
                $user->getDetails()->changeSite($data['details']['site']);
            }

            $user = $this->userRepository->updateUser($user, $crypto);
            return new UserResponse($user);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}