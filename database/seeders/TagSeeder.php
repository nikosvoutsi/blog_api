<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'gadgets',
            'mobile'
        ];

        foreach ($tags as $title) {
            Tag::create(['title' => $title]);
        }
    }
}
