<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use Tests\TestCase;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CastMemberUnitTest extends TestCase
{
    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }

    public function testFillableAttribute()
    {
        $fillable = ["name", "type"];
        $this->assertEquals($fillable, $this->castMember->getFillable());
    }

    public function testCastsAttribute()
    {
        $casts = ["id" => "string", "type" => "integer"];
        $this->assertEquals($casts, $this->castMember->getCasts());
    }

    public function testIncrementsAttribute()
    {
        $this->assertFalse($this->castMember->incrementing);
    }

    public function testDatesAttribute()
    {
        $dates = ["deleted_at", "created_at", "updated_at"];
        $categoryDates = $this->castMember->getDates();
        $this->assertEqualsCanonicalizing($dates, $categoryDates);
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];
        $categoryTraits = array_values(class_uses(CastMember::class));

        $this->assertEquals($traits, $categoryTraits);
    }
}