<?php

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = \App\Models\Category::all();
        factory(Genre::class, 100)
            ->create()
            ->each(function (Genre $genre) use ($categories) {
                $categoriesId = $categories->random(5)->pluck("id")->toArray();
                $genre->categories()->attach($categoriesId);
            });
    }
}