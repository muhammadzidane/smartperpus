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
            array(
                'Muhammad',
                'Zidane',
                'muhammmadzidanealsaadawi@gmail.com',
                'super_admin',
                Hash::make('zidane123'),
                '2000-07-19',
                'L',
                '0895364040902',
            ),
            array(
                'Rudy',
                'Greyrat',
                'rudy@gmail.com',
                'guest',
                Hash::make('rudy123'),
                null,
                null,
                null,
            ),
            array(
                'Silvi',
                'Putri',
                'silvi@gmail.com',
                'admin',
                Hash::make('silvi123'),
                '1998-02-22',
                'P',
                '089528229989',
            ),
        );

        foreach ($users as $user) {
            User::create(
                array(
                    'first_name'    => $user[0],
                    'last_name'     => $user[1],
                    'email'         => $user[2],
                    'role'          => $user[3],
                    'password'      => $user[4],
                    'date_of_birth' => $user[5],
                    'gender'        => $user[6],
                    'phone_number'  => $user[7],
                )
            );
        }
    }
}
