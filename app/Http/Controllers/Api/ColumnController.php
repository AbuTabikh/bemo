<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Column;
use App\Http\Requests\Column\StoreColumnRequest;

class ColumnController extends Controller
{
    public function index()
    {
        return Column::with(['cards' => function ($query) {
            $query->orderBy('order');
        }])->orderBy('order')->get();
    }


    public function store(StoreColumnRequest $request)
    {
        $column = Column::create([
            'title' => $request->title,
            'order' => $request->order,
        ]);
        return $column->load('cards');
    }


    public function destroy(Column $column)
    {
        $column->cards()->delete();
        return $column->delete();
    }
}
