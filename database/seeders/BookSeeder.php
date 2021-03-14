<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comic_books = array(
            array(
                'name'      => 'Jujutsu Kaisen 01',
                'price'     => 40000,
                'image'     => 'jujutsu-kaisen-01.jpg',
                'author_id' => 1,
            ),
            array(
                'name'      => 'Demon Slayer: Kimetsu No Yaiba 01',
                'price'     => 40000,
                'image'     => 'kemetsu-no-yaiba.jpg',
                'author_id' => 2,
            ),
            array(
                'name'      => 'Detektif Conan 96',
                'price'     => 25000,
                'image'     => 'detektif-conan-96.jpg',
                'author_id' => 3,
            ),
            array(
                'name'      => 'Fairy Tail 100 Years Quest 01',
                'price'     => 28000,
                'image'     => 'fairy-tail-100-years-quest-01.jpg',
                'author_id' => 4
            ),
            array(
                'name'      => 'Haikyu!!: Fly High! Volleyball! 21',
                'image'     => 'haikyuu-fly-high-volleybal-21',
                'price'     => 35000,
                'author_id' => 5,
            ),
            array(
                'name'      => 'Jujutsu Kaisen 02',
                'price'     => 40000,
                'image'     => 'jujutsu-kaisen-02.jpg',
                'author_id' => 1,
            ),
            array(
                'name'      => 'Demon Slayer: Kimetsu No Yaiba 02',
                'price'     => 40000,
                'image'     => 'kemetsu-no-yaiba.jpg',
                'author_id' => 2,
            ),
            array(
                'name'      => 'Detektif Conan 97',
                'price'     => 25000,
                'image'     => 'detektif-conan-97.jpg',
                'author_id' => 3,
            ),
            array(
                'name'      => 'Fairy Tail 100 Years Quest 02',
                'price'     => 28000,
                'image'     => 'fairy-tail-100-years-quest-02.jpg',
                'author_id' => 4
            ),
            array(
                'name'      => 'Haikyu!!: Fly High! Volleyball! 22',
                'image'     => 'haikyuu-fly-high-volleybal-22',
                'price'     => 35000,
                'author_id' => 5,
            ),
        );

        foreach ($comic_books as $book) {
            Book::create(
                array(
                    'name'        => $book['name'],
                    'price'       => $book['price'],
                    'image'       => $book['image'],
                    'author_id'   => $book['author_id'],
                )
            );
        }

        // Book::create(
        //     array(
        //         'name'        => 'Kimetsu Mugen Train',
        //         'price'       => 23400,
        //         'image'       => 'kimetsu-mugen-train.jpg',
        //         'author_id'   => 2,
        //     )
        // );
    }
}
