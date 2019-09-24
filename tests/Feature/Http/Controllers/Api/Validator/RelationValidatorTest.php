<?php


namespace Tests\Feature\Http\Controllers\Api\Validator;


use App\Models\Category;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RelationValidatorTest extends TestCase
{
    use DatabaseMigrations;
    private $rules = [
        "genres_id" => "relations:categories_id",
        "categories_id" => "relations:genres_id",
    ];
    protected function setUp(): void
    {
        parent::setUp();
        $this->app["validator"]->extend("relations", 'App\Http\Controllers\Api\Validator\RelationValidator@relations');
    }

    public function testValidateRelations()
    {

        $data = [
            [
                "categories_id" => ["a"],
                "genres_id" => ["b"]
            ],
            [
                "categories_id" => ["a", "b"],
                "genres_id" => ["c"]
            ],
            [
                "categories_id" => ["a"],
                "genres_id" => ["b", "c"]
            ]
        ];
        foreach ($data as $value) {
            $validator = $this->app["validator"]->make($value, $this->rules);
            $this->assertFalse($validator->passes());
        }
    }

    public function testValidateRelationsIfRealIds()
    {
        $categories = factory(Category::class, 3)->create();
        $genres = factory(Genre::class, 2)->create();
        $syncArray = [];
        $genresArray = [];
        foreach ($categories as $category){
            array_push($syncArray, $category->id);
        }
        foreach ($genres as $genre) {
            array_push($genresArray, $genre->id);
            $genre->categories()->sync($syncArray);
        }
        $data = [
            [
                "categories_id" => [$syncArray[0]],
                "genres_id" => [$genresArray[0]]
            ],
            [
                "categories_id" => $syncArray,
                "genres_id" => $genresArray
            ]
        ];
        foreach ($data as $value) {
            $validator = $this->app["validator"]->make($value, $this->rules);
            $this->assertTrue($validator->passes());
        }
    }
}
