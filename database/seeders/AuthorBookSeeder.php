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

    }
}
