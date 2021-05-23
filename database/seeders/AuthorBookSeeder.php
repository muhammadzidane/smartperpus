<?php

namespace Database\Seeders;
use App\Models\{Author, Book};

use Illuminate\Database\Seeder;

class AuthorBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $j = 6;
        for ($i=1; $i <= 5; $i++) {
            Author::find($i)->books()->attach(Book::find($i));
            Author::find($i)->books()->attach(Book::find($j));
            $j++;
        }

        // Author::find(6)->books()->attach(Book::find(11));
        // Author::find(7)->books()->attach(Book::find(12));
        // Author::find(8)->books()->attach(Book::find(13));
        // Author::find(9)->books()->attach(Book::find(14));
        // Author::find(10)->books()->attach(Book::find(15));
        // Author::find(11)->books()->attach(Book::find(16));
        // Author::find(12)->books()->attach(Book::find(17));
        // Author::find(13)->books()->attach(Book::find(18));
        // Author::find(14)->books()->attach(Book::find(19));
        // Author::find(15)->books()->attach(Book::find(20));
        // Author::find(16)->books()->attach(Book::find(21));
        // Author::find(17)->books()->attach(Book::find(22));

        $k = 11;
        for ($i=6; $i <= 18 ; $i++) {
            Author::find($i)->books()->attach(Book::find($k++));
        }

        $buku_masak = 24;
        for ($i=19; $i <= 25 ; $i++) {
            Author::find($i)->books()->attach(Book::find($buku_masak++));
        }

    }
}
