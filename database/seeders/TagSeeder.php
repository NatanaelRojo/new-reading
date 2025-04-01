<?php

namespace Database\Seeders;

use App\Models\API\V1\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('tags.default_tags') as $tagName) {
            Tag::query()->firstOrCreate([
                'name' => $tagName,
            ], [
                'name' => $tagName,
            ]);
        }
    }
}
