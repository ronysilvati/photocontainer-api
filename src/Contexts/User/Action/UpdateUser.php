<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Command\UpdateUserCommand;
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

    const PHOTOGRAPHER = 2;

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
     * @param UpdateUserCommand $command
     * @return UserResponse
     * @throws \Exception
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     */
    public function handle(UpdateUserCommand $command): UserResponse
    {
        /** @var User $user */
        $user = $this->userRepository->findUser($command->getId());

        !$command->getName()?: $user->changeName($command->getName());
        !$command->getEmail() ?: $user->changeEmail($command->getEmail());

        if ($command->getProfileId() === self::PHOTOGRAPHER) {
            $photographerDetails = new PhotographerDetails(
                $command->getBio(),
                $command->getStudio(),
                $command->getNameType()
            );

            $user->getDetails()->changePhographerDetails($photographerDetails);
        }

        !$command->getPassword() ?: $user->changePwd($this->cryptoMethod->hash($command->getPassword()));

        if ($command->isHasDetails()) {
            $user->changeDetails($this->updateDetails($user->getDetails(), $command));
        }

        if ($command->isHasAddress()) {
            $address = $this->updateAddress($user, $command);
            $user->changeAddress($address);
        }

        $user = $this->userRepository->updateUser($user);
        return new UserResponse($user);
    }

    /**
     * @param User $user
     * @param UpdateUserCommand $command
     * @return Address
     * @throws \Exception
     */
    private function updateAddress(User $user, UpdateUserCommand $command): Address
    {
        $address = $user->getAddress() ?? new Address();

        !$command->getCountry() ?: $address->changeCountry($command->getCountry());
        !$command->getZipcode() ?: $address->changeZipcode($command->getZipcode());
        !$command->getState() ?: $address->changeState($command->getState());
        !$command->getCity() ?: $address->changeCity($command->getCity());
        !$command->getNeighborhood() ?: $address->changeNeighborhood($command->getNeighborhood());
        !$command->getStreet() ?: $address->changeStreet($command->getStreet());
        !$command->getComplement() ?: $address->changeComplement($command->getComplement());

        return $address;
    }

    /**
     * @param Details $details
     * @param UpdateUserCommand $command
     * @return Details
     */
    private function updateDetails(Details $details, UpdateUserCommand $command): Details
    {
        !$command->getBlog() ?: $details->changeBlog($command->getBlog());
        !$command->getFacebook() ?: $details->changeFacebook($command->getFacebook());
        !$command->getPinterest() ?: $details->changePinterest($command->getPinterest());
        !$command->getInstagram() ?: $details->changeInstagram($command->getInstagram());
        !$command->getPhone() ?: $details->changePhone($command->getPhone());
        !$command->getBirth() ?: $details->changeBirth($command->getBirth());
        !$command->getSite() ?: $details->changeSite($command->getSite());

        return $details;
    }
}
