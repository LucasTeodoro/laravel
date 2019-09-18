<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\VideoController;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Support\Facades\Request;
use Tests\Exceptions\TestException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

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

    public function testInvalidationRequired()
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

    public function testInvalidationMax()
    {
        $data = [
            "title" => str_repeat('a', 256),
        ];
        $this->assertInvalidationInStoreAction($data, "max.string", ["max" => 255]);
        $this->assertInvalidationInUpdateAction($data, "max.string", ["max" => 255]);
    }

    public function testInvalidationInteger()
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

        foreach ($data as $value) {
            $response = $this->assertStore(
                $value['send_data'],
                $value['test_data'] + ['deleted_at' => null]
            );
            $response->assertJsonStructure([
                "created_at", "updated_at"
            ]);
            $response = $this->assertUpdate(
                $value['send_data'],
                $value['test_data'] + ['deleted_at' => null]
            );
            $response->assertJsonStructure([
                "created_at", "updated_at"
            ]);

        }

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
        $controller = $this->mockController("rulesStore");

        $request = \Mockery::mock(Request::class);
        try {
            $controller->store($request);
        } catch (TestException $exception){
            $this->assertCount(1, Video::all());
        }
    }

    public function testRollbackUpdate()
    {
        $controller = $this->mockController("rulesUpdate");

        $request = \Mockery::mock(Request::class);
        try {
            $controller->update($request, $this->video->id);
        } catch (TestException $exception){
            $this->assertEquals($this->video->toArray(), Video::findOne()->toArray());
        }
    }

    protected function mockController($rulesToMock)
    {
        $controller = \Mockery::mock(VideoController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller->shouldReceive("validate")
            ->withAnyArgs()
            ->andReturn($this->sendData);

        $controller->shouldReceive($rulesToMock)
            ->withAnyArgs()
            ->andReturn([]);

        $controller->shouldReceive("handleRelations")
            ->once()
            ->andThrow(new TestException());

        return $controller;
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
}
