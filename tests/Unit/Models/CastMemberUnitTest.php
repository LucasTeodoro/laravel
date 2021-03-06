<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;

class CastMemberUnitTest extends TestCase
{
    private $castMember;

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

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }
}
