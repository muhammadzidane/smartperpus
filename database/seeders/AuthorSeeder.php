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
            'Oh Su Hyang', // 6
            'Bright Learning Center', // 7
            'DIRAMOTI BENEDICT; KLUB BAHASA PANDA', // 8
            'Tim Penggagas', // 9
            'Ruri Widaningsih', // 10
            'Sri Ratna Dewi', // 11
            'Muryani J. Semita', // 12
            'Henry Manampiring', // 13
            'Drs. Gatot Harmanto, M.Pd. dan Rudi Hartono, S.Pd.', // 14
            'Irma Nirmala', // 15
            'Areum', // 16
            'Langenscheidt', // 17
            'Kalam Ilahi', // 18
            'Agus Tempe', // 19
            'Kristien Yuliana', // 20
            'Debbie S. Suryawan', // 21
            'Frida Rezania', // 22
            'Andiri Putri Pribadini', // 23
            'Mince Sriwati', // 24
            'Lidya Tjahyadi', // 25

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
