<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Illuminate\Database\QueryException;

class VideoTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'title' => "title",
            'description' => "description",
            'year_launched' => 2019,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90
        ];
    }

    public function testList()
    {
        $videosFields = [
            'id',
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
            "created_at",
            "updated_at",
            "deleted_at"
        ];
        factory(Video::class, 1)->create();
        $videos = Video::all();
        $videosKey = array_keys($videos->first()->getAttributes());
        $this->assertCount(1, $videos);
        $this->assertEqualsCanonicalizing($videosFields, $videosKey);
    }

    public function testCreate()
    {
        $video = Video::create($this->data);
        $video->refresh();
        $this->assertTrue(Uuid::isValid($video->id));
        $this->assertDatabaseHas('videos', $this->data + ['opened' => false]);
        $this->assertFalse($video->opened);

        $video = Video::create($this->data + ["opened" => true]);
        $this->assertTrue($video->opened);
        $this->assertDatabaseHas('videos', ['opened' => true]);
    }

    public function testCreateWithRelations()
    {
        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();

        $video = Video::create($this->data + [
                "categories_id" => [$category->id],
                "genres_id" => [$genre->id]
            ]);

        $this->assertHasCategory($video->id, $category->id);
        $this->assertHasGenre($video->id, $genre->id);
    }

    public function testRollbackCreate()
    {
        $hasError = false;
        try {
            Video::create($this->data + [
                    "categories_id" => [1,2,3]
                ]);
        } catch (QueryException $e) {
            $this->assertCount(0, Video::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testUpdate()
    {
        $video = factory(Video::class)->create(["opened" => false])->first();
        $video->update($this->data);
        $this->assertFalse($video->opened);
        $this->assertDatabaseHas('videos', $this->data + ["opened" => false]);

        $video = factory(Video::class)->create(["opened" => false])->first();
        $video->update($this->data + ["opened" => true]);
        $this->assertTrue($video->opened);
        $this->assertDatabaseHas('videos', $this->data + ["opened" => true]);
    }

    public function testUpdateWithRelations()
    {
        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();
        $video = factory(Video::class)->create();
        $video->update($this->data + [
            "categories_id" => [$category->id],
            "genres_id" => [$genre->id]
        ]);

        $this->assertHasCategory($video->id, $category->id);
        $this->assertHasGenre($video->id, $genre->id);
    }

    public function testRollbackUpdate()
    {
        $hasError = false;
        $video = factory(Video::class)->create();
        $oldTitle = $video->title;
        try {
            $video->update($this->data + [
                    "categories_id" => [1,2,3],
                ]);
        } catch (QueryException $e) {
            $this->assertDatabaseHas('videos', ['title' => $oldTitle]);
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testHandleRelations()
    {
        $video = factory(Video::class)->create();
        Video::handleRelations($video, []);
        $this->assertCount(0, $video->categories);
        $this->assertCount(0, $video->genres);

        $category = factory(Category::class)->create();
        Video::handleRelations($video, [
            "categories_id" => [$category->id]
        ]);
        $video->refresh();
        $this->assertCount(1, $video->categories);

        $genre = factory(Genre::class)->create();
        Video::handleRelations($video, [
           "genres_id" => [$genre->id]
        ]);
        $video->refresh();
        $this->assertCount(1, $video->genres);

        $video->categories()->delete();
        $video->genres()->delete();

        Video::handleRelations($video, [
           "categories_id" => [$category->id],
           "genres_id" => [$genre->id]
        ]);
        $video->refresh();
        $this->assertCount(1, $video->categories);
        $this->assertCount(1, $video->genres);
    }

    public function testDelete()
    {
        factory(Video::class, 1)->create();
        Video::first()->delete();
        $videos = Video::all();
        $this->assertCount(0, $videos);
    }

    public function testSyncCategories()
    {
        $categoriesId = factory(Category::class, 3)->create()->pluck('id')->toArray();
        $video = factory(Video::class)->create();

        Video::handleRelations($video, [
            'categories_id' => [$categoriesId[0]]
        ]);
        $this->assertHasCategory($video->id, $categoriesId[0]);

        Video::handleRelations($video, [
            "categories_id" => [$categoriesId[1], $categoriesId[2]]
        ]);
        $this->assertDatabaseMissing('category_video', [
            "category_id" => $categoriesId[0],
            "video_id" => $video->id
        ]);
        $this->assertHasCategory($video->id, $categoriesId[1]);
        $this->assertHasCategory($video->id, $categoriesId[2]);
    }

    public function testSyncGenres()
    {
        $genreId = factory(Genre::class, 3)->create()->pluck('id')->toArray();
        $video = factory(Video::class)->create();

        Video::handleRelations($video, [
            'genres_id' => [$genreId[0]]
        ]);
        $this->assertHasGenre($video->id, $genreId[0]);

        Video::handleRelations($video, [
            "genres_id" => [$genreId[1], $genreId[2]]
        ]);
        $this->assertDatabaseMissing('genre_video', [
            "genre_id" => $genreId[0],
            "video_id" => $video->id
        ]);
        $this->assertHasCategory($video->id, $genreId[1]);
        $this->assertHasCategory($video->id, $genreId[2]);
    }

    protected function assertHasCategory(string $video, string $category)
    {
        $this->assertDatabaseHas("category_video", [
            "category_id" => $category,
            "video_id" => $video
        ]);
    }

    protected function assertHasGenre(string $video, string $genre)
    {
        $this->assertDatabaseHas("genre_video", [
            "genre_id" => $genre,
            "video_id" => $video
        ]);
    }
}
