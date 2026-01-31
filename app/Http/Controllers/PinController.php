<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PinController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Tampilkan halaman pin milik user
     */
    public function myPins()
    {
        $pins = Auth::user()->pins()->with("board")->latest()->paginate(12);
        return view("pins.my-pins", compact("pins"));
    }

    /**
     * Form buat pin baru
     */
    public function create()
    {
        $boards = Auth::user()->boards;
        return view("pins.create", compact("boards"));
    }

    /**
     * Simpan pin baru ke Cloudinary dan Database
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

        $board = Board::where("id", $request->board_id)
            ->where("user_id", Auth::id())
            ->firstOrFail();

        // LOGIKA CLOUDINARY: Upload file ke awan, bukan ke folder Vercel
        $imagePath = null;
        if ($request->hasFile("image")) {
            $imagePath = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
        }

        // Simpan URL dari Cloudinary ke database TiDB
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
            ->with("success", "Pin berhasil dibuat melalui Cloudinary!");
    }

    /**
     * Tampilkan detail pin
     */
    public function show(Pin $pin)
    {
        $pin->load("user", "board", "comments.user", "likes");
        return view("pins.show", compact("pin"));
    }

    /**
     * Form edit pin
     */
    public function edit(Pin $pin)
    {
        if ($pin->user_id !== Auth::id()) {
            abort(403);
        }

        $boards = Auth::user()->boards;
        return view("pins.edit", compact("pin", "boards"));
    }

    /**
     * Update pin (Upload ulang ke Cloudinary jika ada gambar baru)
     */
    public function update(Request $request, Pin $pin)
    {
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

        Board::where("id", $request->board_id)
            ->where("user_id", Auth::id())
            ->firstOrFail();

        $updateData = [
            "board_id" => $request->board_id,
            "title" => $request->title,
            "description" => $request->description,
            "link" => $request->link,
        ];

        // Jika user upload gambar baru saat edit
        if ($request->hasFile("image")) {
            $updateData["image_url"] = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
        }

        $pin->update($updateData);

        return redirect()
            ->route("pins.my-pins")
            ->with("success", "Pin berhasil diperbarui!");
    }

    /**
     * Hapus pin
     */
    public function destroy(Pin $pin)
    {
        if ($pin->user_id !== Auth::id()) {
            abort(403);
        }

        // Catatan: File di Cloudinary tidak otomatis terhapus dengan kodingan simpel ini, 
        // tapi record di database akan hilang.
        $pin->delete();

        return redirect()
            ->route("pins.my-pins")
            ->with("success", "Pin berhasil dihapus!");
    }

    /**
     * Fitur Like
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

        return response()->json([
            "liked" => $liked,
            "likes_count" => $pin->likes()->count(),
        ]);
    }

    /**
     * Pencarian Pin
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
     * Halaman Explore
     */
    public function explore()
    {
        $pins = \App\Models\Pin::with("user")->latest()->paginate(20);
        return view("explore.explore", compact("pins"));
    }

    /**
     * Simpan pin orang lain ke board sendiri
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
            return response()->json(["success" => false, "message" => "Board tidak ditemukan"], 403);
        }

        if ($pin->board_id == $board->id) {
            return response()->json(["success" => false, "message" => "Pin sudah ada di board ini"]);
        }

        Pin::create([
            "user_id" => Auth::id(),
            "board_id" => $board->id,
            "title" => $pin->title,
            "description" => $pin->description,
            "image_url" => $pin->image_url,
            "link" => $pin->link,
        ]);

        return response()->json(["success" => true, "message" => "Pin berhasil disimpan!"]);
    }
}