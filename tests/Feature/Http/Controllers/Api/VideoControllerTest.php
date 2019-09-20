<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\BasicCrudController;
use App\Http\Controllers\Api\VideoController;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\MockController;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves, MockController;

    private $video;
    private $sendData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = factory(Video::class)->create([
            'opened' => false
        ]);
        $this->sendData = [
            'title' => 'title',
            'description' => 'description',
            'year_launched' => 2010,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90,
        ];
    }

    public function testIndex()
    {
        $response = $this->get(route('videos.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->video->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('videos.show', ["video" => $this->video->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->video->toArray());
    }

    public function testInvalidationRequiredFields()
    {
        $data = [
            "title" => "",
            "description" => "",
            "year_launched" => "",
            "rating" => "",
            "duration" => "",
            "categories_id" => "",
            "genres_id" => ""
        ];
        $this->assertInvalidationInStoreAction($data, "required");
        $this->assertInvalidationInUpdateAction($data, "required");
    }

    public function testInvalidationMaxFields()
    {
        $data = [
            "title" => str_repeat('a', 256),
        ];
        $this->assertInvalidationInStoreAction($data, "max.string", ["max" => 255]);
        $this->assertInvalidationInUpdateAction($data, "max.string", ["max" => 255]);
    }

    public function testInvalidationIntegerFields()
    {
        $data = [
            "duration" => "a"
        ];
        $this->assertInvalidationInStoreAction($data, "integer");
        $this->assertInvalidationInUpdateAction($data, "integer");
    }

    public function testInvalidationYearLaunchedField()
    {
        $data = [
            "year_launched" => "a"
        ];
        $this->assertInvalidationInStoreAction($data, "date_format", ["format" => "Y"]);
        $this->assertInvalidationInUpdateAction($data, "date_format", ["format" => "Y"]);
    }

    public function testInvalidationCategoriesIdField()
    {
        $field = "categories_id";
        $this->invalidationArrayField($field);
        $this->invalidationExistsField($field);
    }

    public function testInvalidationGenresIdField()
    {
        $field = "genres_id";
        $this->invalidationArrayField($field);
        $this->invalidationExistsField($field);
    }

    public function testInvalidationRelationsFields()
    {
        $data = [
            "genres_id" => "a",
            "categories_id" => "b"
        ];
        $this->assertInvalidationInStoreAction($data, "relations");
        $this->assertInvalidationInUpdateAction($data, "relations");
    }

    public function testInvalidationOpenedField()
    {
        $data = [
            "opened" => "a"
        ];
        $this->assertInvalidationInStoreAction($data, "boolean");
        $this->assertInvalidationInUpdateAction($data, "boolean");
    }

    public function testInvalidationRatingField()
    {
        $data = [
            "rating" => 0
        ];
        $this->assertInvalidationInStoreAction($data, "in");
        $this->assertInvalidationInUpdateAction($data, "in");
    }

    public function testSave()
    {
        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();
        $genre->categories()->sync([$category->id]);
        $foreign = ["categories_id" => [$category->id], "genres_id" => [$genre->id]];
        $data = [
            [
                'send_data' => $this->sendData + $foreign,
                'test_data' => $this->sendData + ["opened" => false]
            ],
            [
                'send_data' => $this->sendData + $foreign + ["opened" => true],
                'test_data' => $this->sendData + ["opened" => true]
            ],
            [
                'send_data' => $this->sendData + $foreign + ["rating" => Video::RATING_LIST[1]],
                'test_data' => $this->sendData + ["rating" => Video::RATING_LIST[1]]
            ],
        ];

        $this->assertSave($data);
    }

    public function testDestroy()
    {
        $response = $this->json(
            "DELETE",
            route("videos.destroy", ["video" => $this->video->id])
        );

        $response
            ->assertStatus(204);
        $this->assertNull(Video::find($this->video->id));
        $this->assertNotNull(Video::withTrashed()->find($this->video->id));
    }

    public function testRollbackStore()
    {
        $this->assertRollbackStore();
    }

    public function testRollbackUpdate()
    {
        $this->assertRollbackUpdate($this->video);
    }

    protected function invalidationArrayField($field)
    {
        $data = [
            $field => "a"
        ];
        $this->assertInvalidationInStoreAction($data, "array");
        $this->assertInvalidationInUpdateAction($data, "array");
    }

    protected function invalidationExistsField($field)
    {
        $data = [
            $field => [100]
        ];
        $this->assertInvalidationInStoreAction($data, "exists");
        $this->assertInvalidationInUpdateAction($data, "exists");
    }

    protected function routeStore()
    {
        return route("videos.store");
    }

    protected function routeUpdate()
    {
        return route("videos.update", ["video" => $this->video->id]);
    }

    protected function model()
    {
        return Video::class;
    }

    protected function controller()
    {
        return VideoController::class;
    }
}