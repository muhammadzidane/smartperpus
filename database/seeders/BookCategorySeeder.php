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
        for ($i=1; $i <= 10; $i++) {
            Book::find($i)->categories()->attach(Category::firstWhere('name', 'Komik'));
        }

        for ($i=11; $i <= 23 ; $i++) {
            Book::find($i)->categories()->attach(Category::firstWhere('name', 'Kamus'));
        }

        for ($i=24; $i <= 30 ; $i++) {
            Book::find($i)->categories()->attach(Category::firstWhere('name', 'Buku Masak'));
        }
    }
}
