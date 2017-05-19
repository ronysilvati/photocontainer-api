<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Persistence;

use Illuminate\Database\Capsule\Manager as DB;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Address;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\PhotographerDetails;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Address as AddressModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Detail;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User as UserModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\UserProfile;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;

class EloquentUserRepository implements UserRepository
{
    /**
     * @var EloquentDatabaseProvider
     */
    private $conn;

    public function __construct(EloquentDatabaseProvider $conn)
    {
        $this->conn = $conn;
    }

    public function createUser(User $user, ?string $encryptedPwd)
    {
        try {
            DB::beginTransaction();

            if ($encryptedPwd !== null) {
                $user->changePwd($encryptedPwd);
            }

            $userModel = new UserModel();
            if ($userModel->where("email", $user->getEmail())->first()) {
                throw new \DomainException("O email nâo está disponível.");
            }

            $userModel = new UserModel();
            $userModel->name = $user->getName();
            $userModel->email = $user->getEmail();
            $userModel->password = $encryptedPwd;
            $userModel->save();

            $user->changeId($userModel->id);

            $detail = new Detail();
            $detail->user()->associate($userModel);

            if ($user->getDetails()) {
                $detail->blog = $user->getDetails()->getBlog();
                $detail->facebook = $user->getDetails()->getFacebook();
                $detail->instagram = $user->getDetails()->getInstagram();
                $detail->pinterest = $user->getDetails()->getPinterest();
                $detail->site = $user->getDetails()->getSite();
                $detail->phone = $user->getDetails()->getPhone();
                $detail->birth = $user->getDetails()->getBirth();
            };

            $detail->save();
            $details = $user->getDetails();
            $details->changeId($detail->id);

            $user->changeDetails($details);

            $profile = new UserProfile();
            $profile->user_id = $user->getId();
            $profile->profile_id = $user->getProfile()->getProfileId();
            $profile->active = $user->getProfile()->getActive();
            $profile->user()->associate($userModel);
            $profile->save();

            DB::commit();

            return $user;
        } catch (\DomainException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            throw new PersistenceException("Erro na criação do usuário!");
        }
    }

    public function findUser(?int $id = null, ?string $email = null): User
    {
        $userModel = $id ? UserModel::find($id) : UserModel::where('email', $email)->first();

        $userModel->load('detail', 'userprofile', 'address');
        $userData = $userModel->toArray();

        $user = new User($userData['id'], $userData['name'], $userData['email']);

        $userProfile = new Profile(
            null,
            $userData['userprofile']['user_id'],
            $userData['userprofile']['profile_id'],
            $userData['userprofile']['active']
        );
        $user->changeProfile($userProfile);

        if (isset($userData['detail']) && $userData['detail']['id'] > 0) {
            $details = new Details(
                $userData['detail']['id'],
                $userData['detail']['blog'] ?? $userData['detail']['blog'],
                $userData['detail']['instagram'],
                $userData['detail']['facebook'],
                $userData['detail']['pinterest'],
                $userData['detail']['site'],
                $userData['detail']['phone'],
                $userData['detail']['birth']
            );

            if ($userProfile->getProfileId() === Profile::PHOTOGRAPHER) {
                $photographerDetails = new PhotographerDetails(
                    $userData['detail']['bio'],
                    $userData['detail']['studio_name'],
                    $userData['detail']['name_by']
                );

                $details->changePhographerDetails($photographerDetails);
            }

            $user->changeDetails($details);
        }

        $address = new Address(null, null, null, null, null, null, null, null, null);
        if (isset($userData['address']) && $userData['address']['id'] > 0) {
            $address->changeId($userData['address']['id']);
            $address->changeUserId($userData['address']['user_id']);
            $address->changeCountry($userData['address']['country']);
            $address->changeZipcode($userData['address']['zipcode']);
            $address->changeState($userData['address']['state']);
            $address->changeCity($userData[ 'address']['city']);
            $address->changeStreet($userData[ 'address']['street']);
            $address->changeNeighborhood($userData['address']['neighborhood']);
            $address->changeComplement($userData['address']['complement']);
        }
        $user->changeAddress($address);

        return $user;
    }

    public function updateUser(User $user, ?string $encryptedPwd)
    {
        try {
            DB::beginTransaction();

            $userModel = UserModel::find($user->getId());

            $userModel->name = $user->getName();
            $userModel->email = $user->getEmail();

            if ($encryptedPwd !== null) {
                $user->changePwd($encryptedPwd);
            }

            $userModel->save();

            if ($user->getDetails()) {
                $detail = Detail::find($user->getDetails()->getId());

                $detail->blog = $user->getDetails()->getBlog();
                $detail->facebook = $user->getDetails()->getFacebook();
                $detail->instagram = $user->getDetails()->getInstagram();
                $detail->pinterest = $user->getDetails()->getPinterest();
                $detail->site = $user->getDetails()->getSite();
                $detail->phone = $user->getDetails()->getPhone();
                $detail->birth = $user->getDetails()->getBirth();

                $phographerDetails = $user->getDetails()->getPhographerDetails();
                if ($phographerDetails) {
                    $detail->studio_name = $phographerDetails->getStudio();
                    $detail->name_by = $phographerDetails->getNameType();
                    $detail->bio = $phographerDetails->getBio();
                }

                $detail->user()->associate($userModel);
                $detail->save();

                $details = $user->getDetails();
                $details->changeId($detail->id);

                $user->changeDetails($details);
            };

            if ($user->getAddress()) {
                $address = $user->getAddress();
                $addressModel = AddressModel::find($address->getId());

                if ($addressModel == null) {
                    $addressModel = new AddressModel();
                }

                $addressModel->country = $address->getCountry();
                $addressModel->state = $address->getState();
                $addressModel->city = $address->getCity();
                $addressModel->neighborhood = $address->getNeighborhood();
                $addressModel->complement = $address->getComplement();
                $addressModel->street = $address->getStreet();
                $addressModel->zipcode = $address->getZipcode();

                $addressModel->user()->associate($userModel);
                $addressModel->save();

                $address->changeId($addressModel->id);

                $user->changeAddress($address);
            }

            return $user;
        } catch (\DomainException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            throw new PersistenceException("Erro na criação do usuário!");
        }
    }
}
