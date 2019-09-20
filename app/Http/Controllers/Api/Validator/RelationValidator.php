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
            if (count($parameters) > 1 or count($parameters) === 0) return false;
            $this->relation = $parameters[0];
            if (count($data[$attribute]) != count($data[$parameters[0]])) return false;
            $table = $this->tableName();
            foreach ($data[$attribute] as $key => $value) {
                $select = \DB::table($table)
                    ->where($this->replace($this->attribute), "=", $value)
                    ->where($this->replace($this->relation), "=", $data[$this->relation][$key])
                    ->first();
                if (!$select) return false;
            }

            return true;
        } catch (\Exception $exception) {
            return false;
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
