<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            array('Muhammad', 'Zidane', 'muhammmadzidanealsaadawi@gmail.com', 'super_admin', Hash::make('zidane123')),
            array('Rudy', 'Greyrat', 'rudy@gmail.com', 'guest', Hash::make('rudy123')),
            array('Silvi', 'Putri', 'silvi@gmail.com', 'admin', Hash::make('silvi123')),
        );

        foreach ($users as $user) {
            User::create(
                array(
                    'first_name' => $user[0],
                    'last_name'  => $user[1],
                    'email'      => $user[2],
                    'role'       => $user[3],
                    'password'   => $user[4],
                )
            );
        }
    }
}
