<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = auth()->user()->boards()->with('pins')->get();
        return response()->json($boards);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_private' => 'boolean',
        ]);

        $board = auth()->user()->boards()->create($validated);
        return response()->json($board, 201);
    }

    public function show(Board $board)
    {
        return response()->json($board->load('pins.user', 'user'));
    }

    public function update(Request $request, Board $board)
    {
        $this->authorize('update', $board);
        
        $validated = $request->validate([
            'name' => 'string',
            'description' => 'nullable|string',
            'is_private' => 'boolean',
        ]);

        $board->update($validated);
        return response()->json($board);
    }

    public function destroy(Board $board)
    {
        $this->authorize('delete', $board);
        $board->delete();
        return response()->json(null, 204);
    }
}
