<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PinController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Display user's pins page
     */
    public function myPins()
    {
        $pins = Auth::user()->pins()->with("board")->latest()->paginate(12);
        return view("pins.my-pins", compact("pins"));
    }

    /**
     * Show the form for creating a new pin
     */
    public function create()
    {
        $boards = Auth::user()->boards;
        return view("pins.create", compact("boards"));
    }

    /**
     * Store a newly created pin in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|max:255",
            "description" => "nullable|max:1000",
            "image" => "required|image|mimes:jpeg,png,jpg,gif|max:5120", // 5MB max
            "board_id" => "required|exists:boards,id",
            "link" => "nullable|url",
        ]);

        // Check if board belongs to user1
        $board = Board::where("id", $request->board_id)
            ->where("user_id", Auth::id())
            ->firstOrFail();

        // Upload image
        $imagePath = null;
        if ($request->hasFile("image")) {
            $image = $request->file("image");
            $imageName =
                time() .
                "_" .
                Str::random(10) .
                "." .
                $image->getClientOriginalExtension();
            $imagePath = $image->storeAs("pins", $imageName, "public");
        }

        // Create pin
        $pin = Pin::create([
            "user_id" => Auth::id(),
            "board_id" => $request->board_id,
            "title" => $request->title,
            "description" => $request->description,
            "image_url" => $imagePath,
            "link" => $request->link,
        ]);

        return redirect()
            ->route("pins.my-pins")
            ->with("success", "Pin berhasil dibuat!");
    }

    /**
     * Display the specified pin
     */
    public function show(Pin $pin)
    {
        $pin->load("user", "board", "comments.user", "likes");
        return view("pins.show", compact("pin"));
    }

    /**
     * Show the form for editing the specified pin
     */
    public function edit(Pin $pin)
    {
        // Check if pin belongs to user
        if ($pin->user_id !== Auth::id()) {
            abort(403);
        }

        $boards = Auth::user()->boards;
        return view("pins.edit", compact("pin", "boards"));
    }

    /**
     * Update the specified pin in storage
     */
    public function update(Request $request, Pin $pin)
    {
        // Check if pin belongs to user
        if ($pin->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            "title" => "required|max:255",
            "description" => "nullable|max:1000",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:5120",
            "board_id" => "required|exists:boards,id",
            "link" => "nullable|url",
        ]);

        // Check if board belongs to user
        $board = Board::where("id", $request->board_id)
            ->where("user_id", Auth::id())
            ->firstOrFail();

        $updateData = [
            "board_id" => $request->board_id,
            "title" => $request->title,
            "description" => $request->description,
            "link" => $request->link,
        ];

        // Handle image upload if new image provided
        if ($request->hasFile("image")) {
            // Delete old image
            if (
                $pin->image_url &&
                Storage::disk("public")->exists($pin->image_url)
            ) {
                Storage::disk("public")->delete($pin->image_url);
            }

            // Upload new image
            $image = $request->file("image");
            $imageName =
                time() .
                "_" .
                Str::random(10) .
                "." .
                $image->getClientOriginalExtension();
            $imagePath = $image->storeAs("pins", $imageName, "public");
            $updateData["image_url"] = $imagePath;
        }

        $pin->update($updateData);

        return redirect()
            ->route("pins.my-pins")
            ->with("success", "Pin berhasil diupdate!");
    }

    /**
     * Remove the specified pin from storage
     */
    public function destroy(Pin $pin)
    {
        // Check if pin belongs to user
        if ($pin->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete image file
        if (
            $pin->image_url &&
            Storage::disk("public")->exists($pin->image_url)
        ) {
            Storage::disk("public")->delete($pin->image_url);
        }

        $pin->delete();

        return redirect()
            ->route("pins.my-pins")
            ->with("success", "Pin berhasil dihapus!");
    }

    /**
     * Toggle like on a pin
     */
    public function toggleLike(Pin $pin)
    {
        $user = Auth::user();

        if ($user->likedPins()->where("pin_id", $pin->id)->exists()) {
            $user->likedPins()->detach($pin->id);
            $liked = false;
        } else {
            $user->likedPins()->attach($pin->id);
            $liked = true;
        }

        $likesCount = $pin->likes()->count();

        return response()->json([
            "liked" => $liked,
            "likes_count" => $likesCount,
        ]);
    }

    /**
     * Search pins
     */
    public function search(Request $request)
    {
        $query = $request->get("q", "");

        $pins = Pin::where("title", "LIKE", "%{$query}%")
            ->orWhere("description", "LIKE", "%{$query}%")
            ->with("user", "board")
            ->latest()
            ->paginate(12);

        return view("pins.search", compact("pins", "query"));
    }

    /**
     * Get pins by board for AJAX
     */
    public function getByBoard(Board $board)
    {
        $pins = $board->pins()->with("user")->latest()->get();

        return response()->json($pins);
    }

    // app/Http/Controllers/PinController.php
    public function explore()
    {
        // Mengambil semua pin, menyertakan data user (eager loading),
        // diurutkan dari yang terbaru, dan dipaginasi 20 item per halaman.
        $pins = \App\Models\Pin::with("user")->latest()->paginate(20);

        // Pastikan path view sesuai dengan folder Anda: resources/views/explore/explore.blade.php
        return view("explore.explore", compact("pins"));
    }

    /**
     * Save pin to board
     */
    public function saveToBoard(Request $request, Pin $pin)
    {
        $request->validate([
            "board_id" => "required|exists:boards,id",
        ]);

        $board = Board::where("id", $request->board_id)
            ->where("user_id", Auth::id())
            ->first();

        if (!$board) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Board tidak ditemukan atau bukan milik Anda",
                ],
                403,
            );
        }

        // Check if pin already saved to this board
        if ($pin->board_id == $board->id) {
            return response()->json([
                "success" => false,
                "message" => "Pin sudah ada di board ini",
            ]);
        }

        // Create a copy of the pin for the user's board
        $newPin = Pin::create([
            "user_id" => Auth::id(),
            "board_id" => $board->id,
            "title" => $pin->title,
            "description" => $pin->description,
            "image_url" => $pin->image_url,
            "link" => $pin->link,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Pin berhasil disimpan ke board",
            "board" => $board,
        ]);
    }
}
