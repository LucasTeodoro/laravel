<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Genre;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        $genresFields = ["id","name","is_active","created_at","updated_at","deleted_at"];
        factory(Genre::class, 1)->create();
        $genres = Genre::all();
        $genresKey = array_keys($genres->first()->getAttributes());
        $this->assertCount(1, $genres);
        $this->assertEqualsCanonicalizing($genresFields, $genresKey);
    }

    public function testCreate()
    {
        $genre = Genre::create(["name" => "test"]);
        $genre->refresh();
        $this->assertEquals("test", $genre->name);

        $this->assertTrue($genre->is_active);

        $genre = Genre::create(["name" => "test", "is_active" => true]);
        $this->assertTrue($genre->is_active);

        $genre = Genre::create(["name" => "test", "is_active" => false]);
        $this->assertFalse($genre->is_active);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create(["is_active" => false])->first();
        $data = [
            "name" => "updated",
            "is_active" => true
        ];
        $genre->update($data);
        foreach($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        factory(Genre::class, 1)->create();
        Genre::first()->delete();
        $genres = Genre::all();
        $this->assertCount(0, $genres);
    }

}
