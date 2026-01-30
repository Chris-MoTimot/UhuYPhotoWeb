<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Pin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Display user's boards
     */
    public function index()
    {
        $boards = Auth::user()
            ->boards()
            ->withCount("pins")
            ->latest()
            ->paginate(12);
        return view("boards.index", compact("boards"));
    }

    /**
     * Show the form for creating a new board
     */
    public function create()
    {
        return view("boards.create");
    }

    /**
     * Store a newly created board in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:255",
            "description" => "nullable|max:1000",
            "is_private" => "boolean",
        ]);

        try {
            $board = Board::create([
                "user_id" => Auth::id(),
                "name" => $request->name,
                "description" => $request->description,
                "is_private" => $request->boolean("is_private", false),
            ]);

            // Handle JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    "success" => true,
                    "message" => "Board berhasil dibuat!",
                    "board" => $board,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Error creating board: " . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Gagal membuat board. Silakan coba lagi.",
                    ],
                    500,
                );
            }

            return redirect()
                ->route("boards.create")
                ->with("error", "Gagal membuat board. Silakan coba lagi.");
        }

        return redirect()
            ->route("boards.index")
            ->with("success", "Board berhasil dibuat!");
    }

    /**
     * Display the specified board and its pins
     */
    public function show(Board $board)
    {
        // Check if user can view this board
        if ($board->is_private && $board->user_id !== Auth::id()) {
            abort(403, "Board ini bersifat private");
        }

        $pins = $board->pins()->with("user")->latest()->paginate(12);
        $board->load("user");

        return view("boards.show", compact("board", "pins"));
    }

    /**
     * Show the form for editing the specified board
     */
    public function edit(Board $board)
    {
        // Check if board belongs to user
        if ($board->user_id !== Auth::id()) {
            abort(403);
        }

        return view("boards.edit", compact("board"));
    }

    /**
     * Update the specified board in storage
     */
    public function update(Request $request, Board $board)
    {
        // Check if board belongs to user
        if ($board->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            "name" => "required|max:255",
            "description" => "nullable|max:1000",
            "is_private" => "boolean",
        ]);

        $board->update([
            "name" => $request->name,
            "description" => $request->description,
            "is_private" => $request->boolean("is_private", false),
        ]);

        return redirect()
            ->route("boards.index")
            ->with("success", "Board berhasil diupdate!");
    }

    /**
     * Remove the specified board from storage
     */
    public function destroy(Board $board)
    {
        // Check if board belongs to user
        if ($board->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if board has pins
        if ($board->pins()->count() > 0) {
            return redirect()
                ->route("boards.index")
                ->with(
                    "error",
                    "Board tidak dapat dihapus karena masih memiliki pin. Hapus semua pin terlebih dahulu.",
                );
        }

        $board->delete();

        return redirect()
            ->route("boards.index")
            ->with("success", "Board berhasil dihapus!");
    }

    /**
     * Get boards for select options (AJAX)
     */
    public function getBoardsForSelect()
    {
        try {
            if (!Auth::check()) {
                return response()->json(
                    [
                        "error" => "Unauthorized",
                        "message" => "User not authenticated",
                    ],
                    401,
                );
            }

            $boards = Auth::user()
                ->boards()
                ->withCount("pins")
                ->select("id", "name")
                ->get();

            return response()->json($boards);
        } catch (\Exception $e) {
            \Log::error("Error fetching boards: " . $e->getMessage());
            return response()->json(
                [
                    "error" => "Server Error",
                    "message" => "Failed to fetch boards",
                ],
                500,
            );
        }
    }
}
