<?php

namespace Tests\Feature\Models;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use DatabaseMigrations;

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
        $data = [
            'title' => "title",
            'description' => "description",
            'year_launched' => 2019,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90
        ];
        $video = Video::create($data);
        $video->refresh();
        $this->assertTrue(Uuid::isValid($video->id));
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $video->{$key});
        }
        $this->assertFalse($video->opened);

        $video = Video::create([
            'title' => "title",
            'description' => "description",
            'year_launched' => 2019,
            'opened' => true,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90
        ]);
        $this->assertTrue($video->opened);
    }

    public function testUpdate()
    {
        $video = factory(Video::class)->create(["opened" => false])->first();
        $data = [
            'title' => "title",
            'description' => "description",
            'year_launched' => 2019,
            'opened' => true,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90
        ];
        $video->update($data);
        foreach($data as $key => $value) {
            $this->assertEquals($value, $video->{$key});
        }
    }

    public function testDelete()
    {
        factory(Video::class, 1)->create();
        Video::first()->delete();
        $videos = Video::all();
        $this->assertCount(0, $videos);
    }
}
