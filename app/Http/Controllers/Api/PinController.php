<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pin;
use Illuminate\Http\Request;

class PinController extends Controller
{
    public function index(Request $request)
    {
        $pins = Pin::with('user', 'board')
            ->latest()
            ->paginate(12);
        return response()->json($pins);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image_url' => 'required|url',
            'board_id' => 'nullable|exists:boards,id',
            'link' => 'nullable|url',
        ]);

        $pin = auth()->user()->pins()->create($validated);
        return response()->json($pin->load('user'), 201);
    }

    public function show(Pin $pin)
    {
        return response()->json($pin->load('user', 'board', 'comments.user', 'likes'));
    }

    public function update(Request $request, Pin $pin)
    {
        $this->authorize('update', $pin);
        
        $validated = $request->validate([
            'title' => 'string',
            'description' => 'nullable|string',
            'board_id' => 'nullable|exists:boards,id',
        ]);

        $pin->update($validated);
        return response()->json($pin);
    }

    public function destroy(Pin $pin)
    {
        $this->authorize('delete', $pin);
        $pin->delete();
        return response()->json(null, 204);
    }

    public function like(Pin $pin)
    {
        auth()->user()->likedPins()->toggle($pin->id);
        return response()->json(['liked' => auth()->user()->likedPins()->where('pin_id', $pin->id)->exists()]);
    }
}
