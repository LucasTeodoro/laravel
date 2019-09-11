<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Illuminate\Http\Request;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;

class BasicCrudControllerTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::dropTable();
        CategoryStub::createTable();
        $this->controller = new CategoryControllerStub();
    }

    protected function tearDown(): void
    {
        CategoryStub::dropTable();
        parent::tearDown();
    }

    public function testIndex()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(["name" => "test", "description" => "test"]);
        $result = $this->controller->index()->toArray();
        $this->assertEquals([$category->toArray()], $result);
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function testInvalidationDataStore()
    {
        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive("all")
            ->once()
            ->andReturn(["name" => '']);
        $this->controller->store($request);
    }

    public function testStore()
    {
        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive("all")
            ->once()
            ->andReturn(["name" => 'test', "description" => 'description']);

        $obj = $this->controller->store($request);
        $this->assertEquals(
            CategoryStub::find(1)->toArray(),
            $obj->toArray()
        );
    }

    public function testIfFindOrFailFetchModel()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(["name" => "test", "description" => "test"]);

        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod("findOrFail");
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [$category->id]);

        $this->assertInstanceOf(CategoryStub::class, $result);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function testIfFindOrFailThrowExceptionWhenIdInvalid()
    {
        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod("findOrFail");
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invokeArgs($this->controller, [0]);
    }

    public function testShow()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(["name" => "test", "description" => "test"]);

        $result = $this->controller->show($category->id);
        $this->assertInstanceOf(CategoryStub::class, $result);
        $this->assertEquals($category->toArray(), $result->toArray());
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function testInvalidationDataUpdate()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(["name" => "test", "description" => "test"]);

        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive("all")
            ->once()
            ->andReturn(["name" => '']);
        $this->controller->update($request, $category->id);
    }

    public function testUpdate()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(["name" => "test", "description" => "test"]);

        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive("all")
            ->once()
            ->andReturn(["name" => 'teste2', 'description' => 'teste']);
        $categoryUpdated = $this->controller->update($request, $category->id);

        $this->assertInstanceOf(CategoryStub::class, $categoryUpdated);
        $this->assertEquals(
            CategoryStub::find(1)->toArray(),
            $categoryUpdated->ToArray()
        );
    }

    public function testDestroy()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(["name" => "test", "description" => "test"]);
        $this->controller->destroy($category->id);
        $this->assertNull(CategoryStub::find($category->id));
    }

}
