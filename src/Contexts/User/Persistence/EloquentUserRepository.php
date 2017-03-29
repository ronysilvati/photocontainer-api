<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Persistence;

use Illuminate\Database\Capsule\Manager as DB;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Detail;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User as UserModel;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\UserProfile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use Respect\Validation\Exceptions\DomainException;

class EloquentUserRepository implements UserRepository
{
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
                $detail->linkedin = $user->getDetails()->getLinkedin();
                $detail->site = $user->getDetails()->getSite();
                $detail->gender = $user->getDetails()->getGender();
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

    public function findUser(int $id, string $email = null)
    {
        $userModel = $id ? UserModel::find($id) : UserModel::where('email', $email)->first();

        $userModel->load('detail', 'userprofile');
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
                $userData['detail']['linkedin'],
                $userData['detail']['site'],
                $userData['detail']['gender'],
                $userData['detail']['phone'],
                $userData['detail']['birth']
            );
            $user->changeDetails($details);
        }

        return $user;
    }

    public function updateUser(User $user, ?string $encryptedPwd)
    {
        try {
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
                $detail->linkedin = $user->getDetails()->getLinkedin();
                $detail->site = $user->getDetails()->getSite();
                $detail->gender = $user->getDetails()->getGender();
                $detail->phone = $user->getDetails()->getPhone();
                $detail->birth = $user->getDetails()->getBirth();

                $detail->user()->associate($userModel);
                $detail->save();

                $details = $user->getDetails();
                $details->changeId($detail->id);

                $user->changeDetails($details);
            };

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