<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        $categoriesFields = ["id", "name", "description", "is_active", "created_at", "updated_at", "deleted_at"];
        factory(Category::class, 1)->create();
        $categories = Category::all();
        $categoriesKey = array_keys($categories->first()->getAttributes());
        $this->assertCount(1, $categories);
        $this->assertEqualsCanonicalizing($categoriesFields, $categoriesKey);
    }

    public function testCreate()
    {
        $category = Category::create(["name" => "test"]);
        $category->refresh();
        $this->assertTrue(Uuid::isValid($category->id));
        $this->assertEquals("test", $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create(["name" => "test", "description" => "teste"]);
        $this->assertEquals("teste", $category->description);

        $category = Category::create(["name" => "test", "description" => null]);
        $this->assertNull($category->description);

        $category = Category::create(["name" => "test", "is_active" => true]);
        $this->assertTrue($category->is_active);

        $category = Category::create(["name" => "test", "is_active" => false]);
        $this->assertFalse($category->is_active);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create(["description" => "teste", "is_active" => false])->first();
        $data = [
            "name" => "updated",
            "description" => "teste_updated",
            "is_active" => true
        ];
        $category->update($data);
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        factory(Category::class, 1)->create();
        Category::first()->delete();
        $categories = Category::all();
        $this->assertCount(0, $categories);
    }

}
