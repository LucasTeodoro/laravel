<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testShow()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show', ["category" => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json("POST", route("categories.store"), []);

        $this->assertInvalidationRequired($response);

        $response = $this->json("POST",route("categories.store"), [
            "name" => str_repeat("a", 256),
            "is_active" => "a"
        ]);
        
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);
        
        $category = factory(Category::class)->create();
        $response = $this->json("PUT",route("categories.update", ["category" => $category->id]), []);

        $this->assertInvalidationRequired($response);

        $response = $this->json("PUT",route("categories.update", ["category" => $category->id]), [
            "name" => str_repeat("a", 256),
            "is_active" => "a"
        ]);
        
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);
    }

    public function testStore()
    {
        $response = $this->json("POST", route("categories.store"), [
            "name" => "test"
        ]);
        
        $id = $response->json("id");
        $category = Category::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($category->toArray());
        $this->assertTrue($response->json("is_active"));
        $this->assertNull($response->json("description"));

        $response = $this->json("POST", route("categories.store"), [
            "name" => "test",
            "is_active" => false,
            "description" => "description"
        ]);
        
        $id = $response->json("id");
        $category = Category::find($id);

        $response
            ->assertJsonFragment([
                "is_active" => false,
                "description" => "description"
            ]);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'is_active' => false,
            "description" => "description"
        ]);
        $response = $this->json(
            "PUT", 
            route("categories.update", ["category" => $category->id]), 
            [
                "name" => "test",
                "is_active" => true,
                "description" => "test"
            ]
        );
        
        $id = $response->json("id");
        $category = Category::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                "is_active" => true,
                "description" => "test"
            ]);

        $response = $this->json(
            "PUT", 
            route("categories.update", ["category" => $category->id]), 
            [
                "name" => "test",
                "description" => ""
            ]
        );

        $response->assertJsonFragment([
            "description" => null
        ]);
    }

    public function testDestroy()
    {
        $category = factory(Category::class)->create();
        $response = $this->json(
            "DELETE", 
            route("categories.destroy", ["category" => $category->id])
        );

        $response
            ->assertStatus(204);
        
        $category = Category::all();
        
        $this->assertCount(0, $category);
    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(["name"])
            ->assertJsonMissingValidationErrors(["is_active"])
            ->assertJsonFragment([
                \Lang::trans('validation.required', ['attribute' => "name"])
            ]);
    }

    protected function assertInvalidationMax(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(["name"])
            ->assertJsonFragment([
                \Lang::trans('validation.max.string', ['attribute' => "name", "max" => 255])
            ]);
    }

    protected function assertInvalidationBoolean(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(["is_active"])
            ->assertJsonFragment([
                \Lang::trans('validation.boolean', ['attribute' => "is active"])
            ]);
    }
}
