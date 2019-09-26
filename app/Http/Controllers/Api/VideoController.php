<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\Transaction;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class VideoController extends BasicCrudController
{
    use Transaction;
    private $rules;

    public function __construct()
    {
        Validator::extend("relations", 'App\Http\Controllers\Api\Validator\RelationValidator@relations');
        $this->rules = [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => 'required|in:' . implode(",", Video::RATING_LIST),
            'duration' => 'required|integer',
            'categories_id' => 'required|array|exists:categories,id|relations:genres_id',
            'genres_id' => 'required|array|exists:genres,id|relations:categories_id'
        ];
    }

    protected function handleRelations(Video $video, Request $request)
    {
        $video->categories()->sync($request->get('categories_id'));
        $video->genres()->sync($request->get('genres_id'));
    }

    protected function model()
    {
        return Video::class;
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
