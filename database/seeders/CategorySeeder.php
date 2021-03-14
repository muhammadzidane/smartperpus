<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = collect(
            array(
                'Komik', 'Aksi', 'Romantis', 'Petualangan', 'Drama',
                'Komedi', 'Horror', 'Tentara', 'Kriminal', 'Fiksi Ilmiah',
                'Fantasi', 'Misteri', 'Biografi', 'Ensiklopedia', 'Kamus',
                'Jurnal', 'Filsafat',
            )
        );

        foreach ($categories as $category) {
            Category::create(
                array(
                    'name' => $category,
                )
            );
        }
    }
}
