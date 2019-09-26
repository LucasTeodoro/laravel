<?php


namespace App\Http\Controllers\Api\Validator;

use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RelationValidator
{
    private $some = [];

    public function relations($attribute, $value, $parameters, $validator)
    {
        try {
            $data = $validator->getData();

            if (count($parameters) > 1 or count($parameters) === 0) {
                throw new \Exception("Parameters is invalid");
            }

            if (!is_array($value)) {
                throw new \Exception("Value is not array");
            }

            $relation = collect($data[$parameters[0]]);

            $this->verifyRelations($value, $attribute, $relation, $parameters);

            return $this->isValid();
        } catch (\Exception $exception) {
            return false;
        }
    }

    private function verifyRelations(array $value, String $attribute, Collection $relation, array $parameters): void
    {
        foreach ($value as $interaction) {
            $select = $this->findRelation($attribute, $parameters, $interaction);

            array_push($this->some, $this->validateRelations($select, $relation, $parameters));
        }
    }

    private function findRelation(String $attribute, array $parameters, String $value): Collection
    {
        return DB::table(
            $this->tableName($attribute, $parameters)
        )->where(
            $this->replace($attribute),
            "=",
            $value
        )->get();
    }

    private function validateRelations(Collection $select, Collection $relation, array $parameters): bool
    {
        if ($select->isEmpty()) {
            return false;
        }

        return $select->some(function ($value) use ($relation, $parameters) {
            return $relation->contains($value->{$this->replace($parameters[0])});
        });
    }

    private function isValid(): bool
    {
        return !in_array(false, $this->some);
    }

    private function tableName(string $attribute, array $parameters): String
    {
        return $this->replace(implode(
            "_",
            Arr::sort([
                substr($attribute, 0, -3),
                substr($parameters[0], 0, -3)
            ])
        ));
    }

    private function replace(String $string): String
    {
        return str_replace(["ies", "s"], ["y", ""], $string);
    }
}
