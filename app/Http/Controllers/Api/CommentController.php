<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Pin;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Pin $pin)
    {
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        $comment = $pin->comments()->create([
            'user_id' => auth()->id(),
            'text' => $validated['text'],
        ]);

        return response()->json($comment->load('user'), 201);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return response()->json(null, 204);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        $comment->update($validated);
        return response()->json($comment);
    }
}
