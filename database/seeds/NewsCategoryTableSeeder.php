<?php

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;

class NewsCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewsCategory::create([
            'category' => 'Olahraga'
        ]);

        NewsCategory::create([
            'category' => 'Politik'
        ]);
    }
}
