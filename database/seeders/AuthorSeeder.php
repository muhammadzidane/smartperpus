<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authors = array(
            'Gege Akutami', //JJK
            'Koyoharu Gotouge', // demon slayer
            'Aoyama Gosho', // conan
            'Haruichi Furudate', // haikyu
            'Hiro Mashima', // fairy tail
        );

        foreach ($authors as $value) {
            Author::create(
                array(
                    'name' => $value,
                )
            );
        }
    }
}
