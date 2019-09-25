<?php


namespace App\Http\Controllers\Api\Validator;

use DB;
use Exception;
use Illuminate\Support\Arr;

class RelationValidator
{
    public function relations($attribute, $value, $parameters, $validator)
    {
        try {
            $some = [];
            $data = $validator->getData();
            if (!is_array($value)) {
                throw new Exception("Value is not array");
            }
            if (count($parameters) > 1 or count($parameters) === 0) {
                throw new Exception("Parameters is invalid");
            }
            $relation = collect($data[$parameters[0]]);
            $table = $this->tableName($attribute, $parameters);
            foreach ($value as $interaction) {
                $select = DB::table($table)
                    ->where($this->replace($attribute), "=", $interaction)
                    ->get();
                if (count($select->toArray()) === 0) {
                    return false;
                }
                array_push($some, $select->some(function ($value) use ($relation, $parameters) {
                    return $relation->contains($value->{$this->replace($parameters[0])});
                }));
            }

            return !in_array(false, $some);
        } catch (Exception $exception) {
            return false;
        }
    }

    private function tableName($attribute, $parameters)
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
