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
                'author_id' => 1,
            ),
            array(
                'name'      => 'Demon Slayer: Kimetsu No Yaiba 01',
                'price'     => 40000,
                'author_id' => 2,
            ),
            array(
                'name'      => 'Detektif Conan 96',
                'price'     => 25000,
                'author_id' => 3,
            ),
            array(
                'name'      => 'Fairy Tail 100 Years Quest 01',
                'price'     => 28000,
                'author_id' => 4
            ),
            array(
                'name' => 'Haikyu!!: Fly High! Volleyball! 21',
                'price' => 35000,
                'author_id' => 5,
            ),
        );

        foreach ($comic_books as $book) {
            Book::create(
                array(
                    'name'        => $book['name'],
                    'price'       => $book['price'],
                    'author_id'   => $book['author_id'],
                    'category_id' => 1,
                )
            );
        }
    }
}
