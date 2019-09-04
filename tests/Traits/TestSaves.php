<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves 
{
    protected function assertStore(array $sentData, array $testDatabase, array $testJsonData = null): TestResponse
    {
        $response = $this->json("POST", $this->routeStore(), $sentData);
        if($response->status() !== 201) 
        {
            throw new Exception("Respose status must be 201, given {$response->status()}:\n{$response->content()}");
        }
        
        $this->assertInDatabase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);

        return $response;
    }

    protected function assertUpdate(array $sentData, array $testDatabase, array $testJsonData = null): TestResponse
    {
        $response = $this->json("PUT", $this->routeUpdate(), $sentData);
        if($response->status() !== 200) 
        {
            throw new Exception("Respose status must be 200, given {$response->status()}:\n{$response->content()}");
        }
        
        $this->assertInDatabase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);

        return $response;
    }

    private function assertInDatabase(TestResponse $response, array $testDatabase)
    {
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + ["id" => $response->json("id")]);
    }

    private function assertJsonResponseContent(TestResponse $response, array $testDatabase, array $testJsonData = null)
    {
        $testResponse = $testJsonData ?? $testDatabase;
        $response->assertJsonFragment($testResponse  + ["id" => $response->json("id")]);
    }
}