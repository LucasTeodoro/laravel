<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class BasicCrudController extends Controller
{
    public function index()
    {
        return $this->model()::all();
    }

    protected abstract function model();

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());
        $obj = $this->model()::create($validatedData);
        $obj->refresh();

        return $obj;
    }

    protected abstract function rulesStore();

    public function show($id)
    {
        return $this->findOrFail($id);
    }

    protected function findOrFail($id)
    {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();

        return $this->model()::where($keyName, $id)->firstOrFail();
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $dataToBeUpdated = $this->findOrFail($id);
        $dataToBeUpdated->update($validatedData);

        return $dataToBeUpdated;
    }

    protected abstract function rulesUpdate();

    public function destroy($id)
    {
        $dataToBeDeleted = $this->findOrFail($id);
        $dataToBeDeleted->delete();

        return response()->noContent();
    }
}
