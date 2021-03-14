<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Book, Category};

class BookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $book_count = \App\Models\Book::count();

        for ($i=1; $i <= $book_count; $i++) {
            Book::find($i)->categories()->attach(Category::find(array(1, 2, 5)));
        }
    }
}
