<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;

class GenreUnitTest extends TestCase
{
    private $genre;

    public function testFillableAttribute()
    {
        $fillable = ["name", "is_active"];
        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    public function testCastsAttribute()
    {
        $casts = ["id" => "string", "is_active" => "boolean"];
        $this->assertEquals($casts, $this->genre->getCasts());
    }

    public function testIncrementsAttribute()
    {
        $this->assertFalse($this->genre->incrementing);
    }

    public function testDatesAttribute()
    {
        $dates = ["deleted_at", "created_at", "updated_at"];
        $genreDates = $this->genre->getDates();
        $this->assertEqualsCanonicalizing($dates, $genreDates);
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];
        $genreTraits = array_values(class_uses(Genre::class));

        $this->assertEquals($traits, $genreTraits);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = new Genre();
    }
}
