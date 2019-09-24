<?php


namespace App\Http\Controllers\Api\Validator;

use Illuminate\Support\Arr;

class RelationValidator
{
    private $attribute;
    private $relation;

    public function relations($attribute, $value, $parameters, $validator){
        try {
            $data = $validator->getData();
            $this->attribute = $attribute;
            if (count($parameters) > 1 or count($parameters) === 0) return "Parameters is invalid";
            $this->relation = $parameters[0];
            $table = $this->tableName();
            foreach ($data[$attribute] as $value) {
                foreach ($data[$parameters[0]] as $relation) {
                    $select = \DB::table($table)
                        ->where($this->replace($this->attribute), "=", $value)
                        ->where($this->replace($this->relation), "=", $relation)
                        ->first();
                    if (!$select) return false;
                }
            }
            return true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    private function tableName()
    {
        return $this->replace(implode(
            "_",
            Arr::sort([
                substr($this->attribute,0,-3),
                substr($this->relation, 0, -3)
            ])
        ));
    }

    private function replace(String $string): String
    {
        return str_replace(["ies", "s"], ["y", ""], $string);
    }
}
