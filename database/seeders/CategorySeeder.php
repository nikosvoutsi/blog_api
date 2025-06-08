<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Technology',
            'Health',
            'Lifestyle',
            'Education',
            'Entertainment'
        ];

        foreach ($categories as $title) {
            Category::create(['title' => $title]);
        }
    }
}

