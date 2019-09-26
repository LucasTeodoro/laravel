<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;
use Lang;

trait TestValidations
{
    protected abstract function model();

    protected function assertArrayField($field)
    {
        $data = [
            $field => "a"
        ];
        $this->assertInvalidationInStoreAction($data, "array");
        $this->assertInvalidationInUpdateAction($data, "array");
    }

    protected function assertInvalidationInStoreAction(
        array $data,
        string $rule,
        array $ruleParams = []
    ) {
        $response = $this->json("POST", $this->routeStore(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected abstract function routeStore();

    protected function assertInvalidationFields(
        TestResponse $response,
        array $fields,
        string $rule,
        array $ruleParams = []
    ) {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $filed) {
            $fieldName = str_replace("_", " ", $filed);
            $response->assertJsonFragment([
                Lang::get("validation.{$rule}", ['attribute' => $fieldName] + $ruleParams)
            ]);
        }
    }

    protected function assertInvalidationInUpdateAction(
        array $data,
        string $rule,
        array $ruleParams = []
    ) {
        $response = $this->json("PUT", $this->routeUpdate(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected abstract function routeUpdate();

    protected function assertExistsField($field)
    {
        $data = [
            $field => [100]
        ];
        $this->assertInvalidationInStoreAction($data, "exists");
        $this->assertInvalidationInUpdateAction($data, "exists");
    }

}
