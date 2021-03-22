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
        $faker = \Faker\Factory::create('id_ID');

        $comic_books = array(
            array(
                'name'         => 'Jujutsu Kaisen 01',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'price'        => 40000,
                'image'        => 'jujutsu-kaisen-01.jpg',
                'author_id'    => 1,
                'rating'       => 4.7,
                'discount'     => null,
                'ebook'        => false,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
            array(
                'name'         => 'Demon Slayer: Kimetsu No Yaiba 01',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'price'        => 40000,
                'image'        => 'kimetsu-no-yaiba-01.jpg',
                'author_id'    => 2,
                'rating'       => 4.9,
                'discount'     => null,
                'ebook'        => false,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
            array(
                'name'         => 'Detektif Conan 96',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'price'        => 25000,
                'image'        => 'detektif-conan-96.jpg',
                'author_id'    => 3,
                'rating'       => 4.5,
                'discount'     => null,
                'ebook'        => true,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
            array(
                'name'         => 'Fairy Tail 100 Years Quest 01',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'price'        => 28000,
                'image'        => 'fairy-tail-100-years-quest-01.jpg',
                'author_id'    => 4,
                'rating'       => 4.7,
                'discount'     => null,
                'ebook'        => false,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
            array(
                'name'         => 'Haikyu!!: Fly High! Volleyball! 21',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'image'        => 'haikyuu-21.jpg',
                'price'        => 35000,
                'author_id'    => 5,
                'rating'       => 5.0,
                'discount'     => null,
                'ebook'        => false,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
            array(
                'name'         => 'Jujutsu Kaisen 02',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'price'        => 40000,
                'image'        => 'jujutsu-kaisen-02.jpg',
                'author_id'    => 1,
                'rating'       => 4.9,
                'discount'     => null,
                'ebook'        => false,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
            array(
                'name'         => 'Demon Slayer: Kimetsu No Yaiba 02',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'price'        => 40000,
                'image'        => 'kimetsu-no-yaiba-02.jpg',
                'author_id'    => 2,
                'rating'       => 4.8,
                'discount'     => 10000,
                'ebook'        => false,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
            array(
                'name'         => 'Detektif Conan 97',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'price'        => 25000,
                'image'        => 'detektif-conan-97.jpg',
                'author_id'    => 3,
                'rating'       => 3.7,
                'discount'     => null,
                'ebook'        => false,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
            array(
                'name'         => 'Fairy Tail 100 Years Quest 02',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'price'        => 28000,
                'image'        => 'fairy-tail-100-years-quest-02.jpg',
                'author_id'    => 4,
                'rating'       => 4.1,
                'discount'     => null,
                'ebook'        => false,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
            array(
                'name'         => 'Haikyu!!: Fly High! Volleyball! 22',
                'isbn'         => $faker->unique()->numerify('978#####'),
                'image'        => 'haikyuu-22.jpg',
                'price'        => 35000,
                'author_id'    => 5,
                'rating'       => 4.8,
                'discount'     => 5000,
                'ebook'        => false,
                'pages'        => $faker->numberBetween(200, 400),
                'release_date' => '2021-01-28',
                'publisher'    => 'Elek Media Komputindo',
                'subtitle'     => 'Indonesia',
                'weight'       => 0.13,
                'width'        => 12.0,
                'height'       => 18.0,
            ),
        );

        foreach ($comic_books as $book) {
            Book::create(
                array(
                    'isbn'         => $book['isbn'],
                    'name'         => $book['name'],
                    'price'        => $book['price'],
                    'image'        => $book['image'],
                    'author_id'    => $book['author_id'],
                    'rating'       => $book['rating'],
                    'discount'     => $book['discount'],
                    'ebook'        => $book['ebook'],
                    'pages'        => $book['pages'],
                    'release_date' => $book['release_date'],
                    'publisher'    => $book['publisher'],
                    'subtitle'     => $book['subtitle'],
                    'weight'       => $book['weight'],
                    'width'        => $book['width'],
                    'height'       => $book['height'],
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
