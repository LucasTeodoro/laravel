<?php


namespace App\Http\Controllers\Api\Traits;


use DB;
use Illuminate\Http\Request;

trait Transaction
{
    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());
        $self = $this;
        $obj = DB::transaction(function () use ($request, $validatedData, $self) {
            $obj = $this->model()::create($validatedData);
            $self->handleRelations($obj, $request);

            return $obj;
        });

        $obj->refresh();
        return $obj;
    }

    protected abstract function rulesStore();

    protected abstract function model();

    protected abstract function handleRelations(
        $obj,
        Request $request
    );

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $self = $this;
        $dataToBeUpdated = $this->findOrFail($id);
        $dataToBeUpdated = DB::transaction(function () use ($request, $validatedData, $dataToBeUpdated, $self) {
            $dataToBeUpdated->update($validatedData);
            $self->handleRelations($dataToBeUpdated, $request);

            return $dataToBeUpdated;
        });

        return $dataToBeUpdated;
    }

    protected abstract function rulesUpdate();
}
