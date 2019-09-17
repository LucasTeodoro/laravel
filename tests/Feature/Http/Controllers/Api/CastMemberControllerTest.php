<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class CastMemberControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    protected $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = factory(CastMember::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('castmembers.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->castMember->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('castmembers.show', ["castmember" => $this->castMember->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->castMember->toArray());
    }

    public function testInvalidationData()
    {
        $data = [
            "name" => ""
        ];
        $this->assertInvalidationInStoreAction($data, "required");
        $this->assertInvalidationInUpdateAction($data, "required");

        $data = [
            "name" => str_repeat("a", 256)
        ];
        $this->assertInvalidationInStoreAction($data, "max.string", ["max" => 255]);
        $this->assertInvalidationInUpdateAction($data, "max.string", ["max" => 255]);

        $data = [
            "type" => "a"
        ];
        $this->assertInvalidationInStoreAction($data, "digits", ["digits" => 1]);
        $this->assertInvalidationInUpdateAction($data, "digits", ["digits" => 1]);

        $data = [
            "type" => 11
        ];
        $this->assertInvalidationInStoreAction($data, "digits", ["digits" => 1]);
        $this->assertInvalidationInUpdateAction($data, "digits", ["digits" => 1]);
    }

    public function testStore()
    {
        $data = [
            "name" => "test",
            "type" => 1
        ];
        $response = $this->assertStore($data, $data +["type" => 1, "deleted_at" => null]);
        $response->assertJsonStructure([
            "created_at", "updated_at"
        ]);

        $data = [
            "name" => "test",
            "type" => 2
        ];

        $this->assertStore($data, $data + ["type" => 2]);
    }

    public function testUpdate()
    {
        $this->castMember = factory(CastMember::class)->create([
            'type' => 2
        ]);

        $data = [
            "name" => "test",
            "type" => 1
        ];

        $this->assertUpdate($data, $data + ["deleted_at" => null]);
    }

    public function testDestroy()
    {
        $response = $this->json(
            "DELETE",
            route("castmembers.destroy", ["castmember" => $this->castMember->id])
        );

        $response
            ->assertStatus(204);
        $this->assertNull(CastMember::find($this->castMember->id));
        $this->assertNotNull(CastMember::withTrashed()->find($this->castMember->id));
    }

    protected function routeStore()
    {
        return route("castmembers.store");
    }

    protected function routeUpdate()
    {
        return route("castmembers.update", ["castmember" => $this->castMember->id]);
    }

    protected function model()
    {
        return CastMember::class;
    }
}
