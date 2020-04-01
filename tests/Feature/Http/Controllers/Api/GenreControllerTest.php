<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\GenreController;
use App\Models\Category;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\MockController;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves, MockController;

    private $genre;
    private $sendData;

    public function testIndex()
    {
        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->genre->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('genres.show', ["genre" => $this->genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->genre->toArray());
    }

    public function testInvalidationRequiredFields()
    {
        $data = [
            "name" => "",
            "categories_id" => ''
        ];
        $this->assertInvalidationInStoreAction($data, "required");
        $this->assertInvalidationInUpdateAction($data, "required");
    }

    public function testInvalidationMaxFields()
    {
        $data = [
            "name" => str_repeat("a", 256)
        ];
        $this->assertInvalidationInStoreAction($data, "max.string", ["max" => 255]);
        $this->assertInvalidationInUpdateAction($data, "max.string", ["max" => 255]);
    }

    public function testInvalidationBooleanFields()
    {
        $data = [
            "is_active" => "a"
        ];
        $this->assertInvalidationInStoreAction($data, "boolean");
        $this->assertInvalidationInUpdateAction($data, "boolean");
    }

    public function testInvalidationCategoriesIdField()
    {
        $field = "categories_id";
        $this->assertArrayField($field);
        $this->assertExistsField($field);
        $category = factory(Category::class)->create();
        $category->delete();
        $this->assertExistsField($field, $category->id);
    }

    public function testSave()
    {
        $categories = factory(Category::class)->create();
        $foreign = ["categories_id" => [$categories->id]];
        $sendActiveFalse = $this->sendData + ["is_active" => false];
        $data = [
            [
                'send_data' => $this->sendData + $foreign,
                'test_data' => $this->sendData + ["is_active" => true]
            ],
            [
                'send_data' => $sendActiveFalse + $foreign,
                'test_data' => $sendActiveFalse
            ]
        ];
        $this->assertSaveIfSyncData($data, ["categories"]);
        $this->assertCount(1, $this->genre->categories()->get()->toArray());
    }

    public function testRollbackStore()
    {
        $this->assertRollbackStore();
    }

    public function testRollbackUpdate()
    {
        $this->assertRollbackUpdate($this->genre);
    }

    public function testDestroy()
    {
        $response = $this->json(
            "DELETE",
            route("genres.destroy", ["genre" => $this->genre->id])
        );

        $response
            ->assertStatus(204);
        $this->assertNull(Genre::find($this->genre->id));
        $this->assertNotNull(Genre::withTrashed()->find($this->genre->id));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = factory(Genre::class)->create([
            "is_active" => true
        ]);
        $this->sendData = [
            "name" => 'test'
        ];
    }

    protected function routeStore()
    {
        return route("genres.store");
    }

    protected function routeUpdate()
    {
        return route("genres.update", ["genre" => $this->genre->id]);
    }

    protected function model()
    {
        return Genre::class;
    }

    protected function controller()
    {
        return GenreController::class;
    }
}
