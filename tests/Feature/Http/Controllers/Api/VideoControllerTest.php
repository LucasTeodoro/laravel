<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\VideoController;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\MockController;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;
use Illuminate\Http\UploadedFile;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves, MockController;

    private $video;
    private $sendData;

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
        $this->assertArrayField($field);
        $this->assertExistsField($field);
        $category = factory(Category::class)->create();
        $category->delete();
        $this->assertExistsField($field, $category->id);
    }

    public function testInvalidationGenresIdField()
    {
        $field = "genres_id";
        $this->assertArrayField($field);
        $this->assertExistsField($field);
        $genre = factory(Genre::class)->create();
        $genre->delete();
        $this->assertExistsField($field, $genre->id);
    }

    public function testInvalidationRelationsFields()
    {
        $data = [
            [
                "genres_id" => ["a"],
                "categories_id" => ["b"]
            ],
            [
                "genres_id" => ["a"],
                "categories_id" => ["b", "c"]
            ],
            [
                "genres_id" => ["a", "b"],
                "categories_id" => ["b", "c"]
            ],

        ];
        foreach ($data as $value) {
            $this->assertInvalidationInStoreAction($value, "genres_has_categories");
            $this->assertInvalidationInUpdateAction($value, "genres_has_categories");
        }
    }

    public function testInvalidationOpenedField()
    {
        $data = [
            "opened" => "a"
        ];
        $this->assertInvalidationInStoreAction($data, "boolean");
        $this->assertInvalidationInUpdateAction($data, "boolean");
    }

    public function testInvalidationVideoFileField()
    {
        $data = [
            "video_file" => "a"
        ];
        $this->assertInvalidationInStoreAction($data, "file");
        $this->assertInvalidationInUpdateAction($data, "file");

        $data = [
            "video_file" => UploadedFile::fake()->create("video.jpeg")
        ];
        $this->assertInvalidationInStoreAction($data, "mimetypes", ["mime" => "video/mp4"]);
        $this->assertInvalidationInUpdateAction($data, "mimetypes", ["mime" => "video/mp4"]);

        $data = [
            "video_file" => UploadedFile::fake()->create("video.mp4")->size(2048)
        ];

        $this->assertInvalidationInStoreAction($data, "max.file", ["max" => "1024"]);
        $this->assertInvalidationInUpdateAction($data, "max.file", ["max" => "1024"]);
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
        \Storage::fake();
        $file = UploadedFile::fake()->create("video.mp4");
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
            [
                'send_data' => $this->sendData + $foreign + ["video_file" => $file],
                'test_data' => $this->sendData + ["video_file" => $file->hashName()]
            ],
        ];

        $this->assertSaveIfSyncData($data, ["categories", "genres"]);
        $this->assertCount(1, $this->video->categories()->get()->toArray());
        $this->assertCount(1, $this->video->genres()->get()->toArray());
        \Storage::assertExists("{$this->video->id}/{$file->hashName()}");
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

//    public function testRollbackStore()
//    {
//        $this->assertRollbackStore();
//    }
//
//    public function testRollbackUpdate()
//    {
//        $this->assertRollbackUpdate($this->video);
//    }

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
