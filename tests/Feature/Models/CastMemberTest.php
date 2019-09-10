<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\CastMember;
use Ramsey\Uuid\Uuid;

class CastMemberTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        $castMembersFields = ["id","name","type","created_at","updated_at","deleted_at"];
        factory(CastMember::class, 1)->create();
        $castMembers = CastMember::all();
        $castMembersKey = array_keys($castMembers->first()->getAttributes());
        $this->assertCount(1, $castMembers);
        $this->assertEqualsCanonicalizing($castMembersFields, $castMembersKey);
    }

    public function testCreate()
    {
        $castMember = CastMember::create(["name" => "test", "type" => 1]);
        $castMember->refresh();
        $this->assertTrue(Uuid::isValid($castMember->id));
        $this->assertEquals("test", $castMember->name);
        $this->assertEquals(1, $castMember->type);
    }

    public function testUpdate()
    {
        $genre = factory(CastMember::class)->create()->first();
        $data = [
            "name" => "updated",
            "type" => 2
        ];
        $genre->update($data);
        foreach($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        factory(CastMember::class, 1)->create();
        CastMember::first()->delete();
        $castMembers = CastMember::all();
        $this->assertCount(0, $castMembers);
    }

}
