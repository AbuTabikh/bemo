<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;

use App\Http\Requests\Card\StoreCardRequest;

use App\Http\Requests\Column\StoreColumnRequest;
use App\Http\Requests\Column\MoveColumnRequest;

class CardController extends Controller
{
    public function index(Request $request)
    {
        $cards = Card::filter($request->only(['date', 'status']))->get();
        return CardResource::collection($cards);
    }

    public function store(StoreColumnRequest $request,Column $column)
    {
        return $column->cards()->create([
            'title' => $request->title,
            'order' => $request->order,
        ]);
    }

    public function update(StoreCardRequest $request,Card $card)
    {

        $card->update([
            'title' => $request->title,
            'description' => $request->description
        ]);
        return $card;
    }

    public function moved(MoveColumnRequest $request,Column $column)
    {
        $cards = $request->cards;

        foreach ($cards as $key => $card) {
            Card::where('id', '=', $card['id'])->update(['order' => $key,'column_id' => $column->id]);
        }

        return response()->noContent();
    }
}
