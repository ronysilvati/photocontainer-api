<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Persistence;

use Illuminate\Database\Capsule\Manager as DB;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Address;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\PhotographerDetails;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\RequestPassword;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Address as AddressModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Detail;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User as UserModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\UserProfile;

use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\RequestPassword as RequestPasswordModel;


class EloquentUserRepository implements UserRepository
{
    /**
     * @param User $user
     * @param null|string $encryptedPwd
     * @return null|User
     * @throws PersistenceException
     */
    public function createUser(User $user, ?string $encryptedPwd): ?\PhotoContainer\PhotoContainer\Contexts\User\Domain\User
    {
        try {
            DB::beginTransaction();

            if ($encryptedPwd !== null) {
                $user->changePwd($encryptedPwd);
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
            }

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
            throw new PersistenceException($e->getMessage(), 'Alguma regra de domínio não foi satisfeita!');
        } catch (\Exception $e) {
            DB::rollback();
            throw new PersistenceException('Erro na criação do usuário!', $e->getMessage());
        }
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isUserUnique(string $email): bool
    {
        return UserModel::where('email', $email)->count() === 0;
    }

    /**
     * @param int $maxSlots
     * @return bool
     */
    public function isUserSlotsAvailable(int $maxSlots): bool
    {
        return UserModel::count() <= $maxSlots;
    }

    /**
     * @param int|null $id
     * @param null|string $email
     * @return null|User
     * @throws PersistenceException
     */
    public function findUser(?int $id = null, ?string $email = null): ?User
    {
        try {
            $userModel = $id ? UserModel::find($id) : UserModel::where('email', $email)->first();

            if (!$userModel) {
                return null;
            }

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

            if (isset($userData['address']) && $userData['address']['id'] > 0) {
                $address = new Address(null, null, null, null, null, null, null, null, null);

                $address->changeId($userData['address']['id']);
                $address->changeUserId($userData['address']['user_id']);
                $address->changeCountry($userData['address']['country']);
                $address->changeZipcode($userData['address']['zipcode']);
                $address->changeState($userData['address']['state']);
                $address->changeCity($userData[ 'address']['city']);
                $address->changeStreet($userData[ 'address']['street']);
                $address->changeNeighborhood($userData['address']['neighborhood']);
                $address->changeComplement($userData['address']['complement']);

                $user->changeAddress($address);
            }

            return $user;
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na criação do usuário!', $e->getMessage());
        }
    }

    /**
     * @param User $user
     * @return null|User
     * @throws PersistenceException
     */
    public function updateUser(User $user): ?\PhotoContainer\PhotoContainer\Contexts\User\Domain\User
    {
        try {
            DB::beginTransaction();

            $userModel = UserModel::find($user->getId());

            $userModel->name = $user->getName();
            $userModel->email = $user->getEmail();

            if ($user->getPwd()) {
                $userModel->password = $user->getPwd();
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
            }

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

            DB::commit();

            return $user;
        } catch (\DomainException $e) {
            DB::rollback();
            throw new PersistenceException($e->getMessage(), 'Alguma regra de domínio não foi satisfeita!');
        } catch (\Exception $e) {
            DB::rollback();
            throw new PersistenceException('Erro na edição do usuário!', $e->getMessage());
        }
    }

    /**
     * @param User $user
     * @return null|RequestPassword
     * @throws PersistenceException
     */
    public function findPwdRequest(User $user): ?RequestPassword
    {
        try {
            /** @var RequestPasswordModel $data */
            $data = RequestPasswordModel::where('user_id', $user->getId())->first();

            if (!$data) {
                return null;
            }

            return new RequestPassword($data->id, $data->token, $data->user_id, new \DateTime($data->valid_until));
        } catch (\Exception $e) {
            throw new PersistenceException('Erro no carregamento de pedido de senha.', $e->getMessage());
        }
    }

    /**
     * @param RequestPassword $requestPassword
     * @return RequestPassword
     * @throws PersistenceException
     */
    public function createPwdRequest(RequestPassword $requestPassword): RequestPassword
    {
        try {
            $reqPwd = new RequestPasswordModel();
            $reqPwd->token = $requestPassword->getToken();
            $reqPwd->user_id = $requestPassword->getUserId();
            $reqPwd->valid_until = $requestPassword->getValidUntil();

            $reqPwd->save();

            return $requestPassword;
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na criação de pedido de senha.', $e->getMessage());
        }
    }

    /**
     * @param RequestPassword $requestPassword
     * @throws PersistenceException
     */
    public function removePwdRequest(RequestPassword $requestPassword): void
    {
        try {
            /** @var RequestPasswordModel $req */
            $req = RequestPasswordModel::find($requestPassword->getId());
            $req->delete();
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na criação de pedido de senha.', $e->getMessage());
        }
    }

    /**
     * @param string $token
     * @return null|RequestPassword
     * @throws PersistenceException
     */
    public function getValidToken(string $token): ?RequestPassword
    {
        try {
            /** @var RequestPasswordModel $data */
            $data = RequestPasswordModel::where('token', $token)->first();
            if (!$data) {
                return null;
            }

            $reqPwd = new RequestPassword($data->id, $data->token, $data->user_id, new \DateTime($data->valid_until));

            if (!$reqPwd->isActive()) {
                return null;
            }

            return $reqPwd;
        } catch (\Exception $e) {
            throw new PersistenceException('Erro no carregamento de pedido de senha.', $e->getMessage());
        }
    }
}
