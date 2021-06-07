<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Runner\Exception;

trait TestSaves
{
    protected function assertSave(array $data)
    {
        foreach ($data as $value) {
            $response = $this->assertStore(
                $value['send_data'],
                $value['test_data'] + ['deleted_at' => null]
            );
            $response->assertJsonStructure([
                "created_at",
                "updated_at"
            ]);

            $response = $this->assertUpdate(
                $value['send_data'],
                $value['test_data'] + ['deleted_at' => null]
            );
            $response->assertJsonStructure([
                "created_at",
                "updated_at"
            ]);
        }
    }

    protected function assertSaveIfSyncData(array $data, array $syncFields)
    {
        foreach ($data as $value) {
            $response = $this->assertStore(
                $value['send_data'],
                $value['test_data'] + ['deleted_at' => null]
            );
            $response->assertJsonStructure([
                "created_at",
                "updated_at"
            ]);
            $this->assertSyncData($response, $syncFields, $value["send_data"]);
            $response = $this->assertUpdate(
                $value['send_data'],
                $value['test_data'] + ['deleted_at' => null]
            );
            $this->assertSyncData($response, $syncFields, $value["send_data"]);
            $response->assertJsonStructure([
                "created_at",
                "updated_at"
            ]);
        }
    }

    protected function assertSyncData(TestResponse $response, array $syncFields, array $data)
    {
        $content = json_decode($response->getContent());
        $this->assertSync($content->id, $syncFields, $data);
    }

    protected function assertSync(String $id, array $fields, array $data)
    {
        $model = $this->model()::find($id);
        foreach ($fields as $field) {
            $this->assertCount(count($data[$field . "_id"]), $model->{$field}()->get()->toArray());
        }
    }

    protected function assertStore(array $sentData, array $testDatabase, array $testJsonData = null): TestResponse
    {
        $response = $this->json("POST", $this->routeStore(), $sentData);
        if ($response->status() !== 201) {
            throw new Exception("Respose status must be 201, given {$response->status()}:\n{$response->content()}");
        }

        $this->assertInDatabase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);

        return $response;
    }

    protected abstract function routeStore();

    private function assertInDatabase(TestResponse $response, array $testDatabase)
    {
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + ["id" => $response->json("id")]);
    }

    protected abstract function model();

    private function assertJsonResponseContent(TestResponse $response, array $testDatabase, array $testJsonData = null)
    {
        $testResponse = $testJsonData ?? $testDatabase;
        $response->assertJsonFragment($testResponse + ["id" => $response->json("id")]);
    }

    protected function assertUpdate(array $sentData, array $testDatabase, array $testJsonData = null): TestResponse
    {
        $response = $this->json("PUT", $this->routeUpdate(), $sentData);
        if ($response->status() !== 200) {
            throw new Exception("Respose status must be 200, given {$response->status()}:\n{$response->content()}");
        }

        $this->assertInDatabase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);

        return $response;
    }

    protected abstract function routeUpdate();
}
