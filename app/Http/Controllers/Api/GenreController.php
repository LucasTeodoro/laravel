<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\Transaction;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends BasicCrudController
{
    use Transaction;

    private $rules = [
        "name" => "required|max:255",
        "is_active" => "boolean",
        "categories_id" => "required|array|exists:categories,id"
    ];

    protected function handleRelations(Genre $genre, Request $request)
    {
        $genre->categories()->sync($request->get('categories_id'));
    }

    protected function model()
    {
        return Genre::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }
}
