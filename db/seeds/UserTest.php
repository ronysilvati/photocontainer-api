<?php

use Phinx\Seed\AbstractSeed;

class UserTest extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $users = $this->table('users');
        $details = $this->table('user_details');
        $profile = $this->table('user_profiles');

        $i = 1;
        do{
            $userData = [
                'id' => $i,
                'name' => "Fotografo{$i}",
                'email' => "fotografo{$i}@mailinator.com",
                'password' => '$2y$10$fkcy8/rbwJHOOYVkm8pWGeuHaFbZgMM.2UDn.1Mht6ryP/zLbgWEC',
            ];
            $users->insert($userData);

            $profileData = [
                'user_id' => $i,
                'profile_id' => 2,
                'active' => 1,
            ];
            $profile->insert($profileData);

            $details->insert(['user_id' => $i]);

            $i++;
        }while($i < 50);

        $users->save();
        $profile->save();
        $details->save();

        do{
            $userData = [
                'id' => $i,
                'name' => "Publisher{$i}",
                'email' => "publisher{$i}@mailinator.com",
                'password' => '$2y$10$fkcy8/rbwJHOOYVkm8pWGeuHaFbZgMM.2UDn.1Mht6ryP/zLbgWEC',
            ];
            $users->insert($userData);

            $profileData = [
                'user_id' => $i,
                'profile_id' => 3,
                'active' => 1,
            ];
            $profile->insert($profileData);

            $details->insert(['user_id' => $i, 'blog' => "http://blog.com"]);

            $i++;
        }while($i < 100);

        $users->save();
        $profile->save();
        $details->save();
    }
}
