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
        $categories = array(
            'Komik',
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
