<?php


namespace Tests\Feature\Http\Controllers\Api\Validator;


use App\Models\Category;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RelationValidatorTest extends TestCase
{
    use DatabaseMigrations;
    private $rules = [
        "genres_id" => "relations:categories_id",
        "categories_id" => "relations:genres_id",
    ];

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
            $this->assertFalse($this->validateRule($value));
        }
    }

    public function testValidateRelationsIfNoRelateData()
    {
        $categories_ids = $this->makeCategories(1);
        $genres_ids = $this->makeGenres(1);
        $noSync = [
            "categories_id" => $this->makeIdsArray($categories_ids),
            "genres_id" => $this->makeIdsArray($genres_ids)
        ];
        $this->assertFalse($this->validateRule($noSync));
    }

    public function testValidateRelationsIfRelateDataOneToOne()
    {
        $categories_ids = $this->makeCategories(1);
        $genres_ids = $this->makeGenres(1);
        $this->makeSync($genres_ids, $categories_ids);

        $data = [
            "categories_id" => $this->makeIdsArray($categories_ids),
            "genres_id" => $this->makeIdsArray($genres_ids)
        ];

        $this->assertTrue($this->validateRule($data));
    }

    public function testValidateRelationsIfRelateDataOneToTwo()
    {
        $categories_ids = $this->makeCategories(2);
        $genres_ids = $this->makeGenres(1);
        $this->makeSync($genres_ids, $categories_ids);

        $data = [
            "categories_id" => $this->makeIdsArray($categories_ids),
            "genres_id" => $this->makeIdsArray($genres_ids)
        ];

        $this->assertTrue($this->validateRule($data));
    }

    public function testValidateRelationsIfRelateDataTwoToTwo()
    {
        $categories_ids = $this->makeCategories(2);
        $genres_ids = $this->makeGenres(2);
        $this->makeSync($genres_ids, $categories_ids);

        $data = [
            "categories_id" => $this->makeIdsArray($categories_ids),
            "genres_id" => $this->makeIdsArray($genres_ids)
        ];

        $this->assertTrue($this->validateRule($data));
    }

    public function testValidateRelationsIfRelateDataAndNotRelateData()
    {
        $categories_ids = $this->makeCategories(2);
        $genres_ids = $this->makeGenres(1);
        $this->makeSync($genres_ids, $categories_ids);
        $categories_ids_not_related = $this->makeCategories(1);
        $data = [
            "categories_id" => array_merge($this->makeIdsArray($categories_ids),
                $this->makeIdsArray($categories_ids_not_related)),
            "genres_id" => $this->makeIdsArray($genres_ids)
        ];

        $this->assertFalse($this->validateRule($data));
    }

    protected function validateRule($value): bool
    {
        $validator = $this->app["validator"]->make($value, $this->rules);
        return $validator->passes();
    }

    protected function makeCategories(int $quantity): Collection
    {
        $categories = factory(Category::class, $quantity)->create();
        return $categories;
    }

    protected function makeGenres(int $quantity): Collection
    {
        $genres = factory(Genre::class, $quantity)->create();
        return $genres;
    }

    private function makeSync(Collection $genres, Collection $categories): void
    {
        foreach ($genres as $genre) {
            $genre->categories()->sync($this->makeIdsArray($categories));
        }
    }

    protected function makeIdsArray(Collection $collection): array
    {
        return $collection->map(function ($value) {
            return $value->id;
        })->toArray();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app["validator"]->extend("relations", 'App\Http\Controllers\Api\Validator\RelationValidator@relations');
    }
}
