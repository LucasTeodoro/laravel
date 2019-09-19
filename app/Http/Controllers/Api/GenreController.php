<?php

namespace App\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends BasicCrudController
{
    private $rules = [
        "name" => "required|max:255",
        "is_active" => "boolean",
        "categories_id" => "required|array|exists:categories,id"
    ];

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());
        $self = $this;
        $obj = \DB::transaction(function () use ($request, $validatedData, $self) {
            /** @var Genre $obj */
            $obj = $this->model()::create($validatedData);
            $self->handleRelations($obj, $request);

            return $obj;
        });

        $obj->refresh();
        return $obj;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $self = $this;
        /** @var Genre $dataToBeUpdated */
        $dataToBeUpdated = $this->findOrFail($id);
        $dataToBeUpdated = \DB::transaction(function () use ($request, $validatedData, $dataToBeUpdated, $self) {
            $dataToBeUpdated->update($validatedData);
            $self->handleRelations($dataToBeUpdated, $request);

            return $dataToBeUpdated;
        });

        return $dataToBeUpdated;
    }

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
