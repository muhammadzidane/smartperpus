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
                'Komik',  'Novel', 'Komedi',  'Fiksi Ilmiah',
                'Fantasi', 'Sejarah', 'Biografi', 'Ensiklopedia', 'Kamus',
                'Jurnal', 'Filsafat', 'Pendidikan', 'Buku Masak', 'Gaya Hidup', 'Seni Dan Desain', 'Sastra',
                'Psikologi', 'Ilmu Sosial', 'Pengembangan Diri', 'Bisnis', 'Teknologi dan Komputer', 'Matematika',
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
