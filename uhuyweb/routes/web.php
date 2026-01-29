<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () {
    if (Auth::check()) {
        return redirect()->route("explore.explore");
    }
    return view("welcome");
})->name("home");

Route::get("/welcome", function () {
    return view("welcome");
})->name("welcome");

Route::get("/explore", [
    \App\Http\Controllers\ExploreController::class,
    "index",
])->name("explore.explore");

Route::get("/login", function () {
    return view("auth.login");
})->name("login");

Route::get("/register", function () {
    return view("auth.register");
})->name("register");

Route::get("/dashboard", function () {
    return view("dashboard");
})
    ->name("dashboard")
    ->middleware("auth");

// Route Login
Route::post("/login", [AuthController::class, "login"])->name("login.post");

// Route Register
Route::post("/register", [AuthController::class, "register"])->name(
    "register.post",
);

// Route Logout
Route::post("/logout", [AuthController::class, "logout"])->name("logout");

// Protected Routes (require authentication)
Route::middleware(["auth"])->group(function () {
    // Pin Routes
    Route::get("/my-pins", [
        App\Http\Controllers\PinController::class,
        "myPins",
    ])->name("pins.my-pins");
    Route::get("/pins/create", [
        App\Http\Controllers\PinController::class,
        "create",
    ])->name("pins.create");
    Route::post("/pins", [
        App\Http\Controllers\PinController::class,
        "store",
    ])->name("pins.store");
    Route::get("/pins/{pin}", [
        App\Http\Controllers\PinController::class,
        "show",
    ])->name("pins.show");
    Route::get("/pins/{pin}/edit", [
        App\Http\Controllers\PinController::class,
        "edit",
    ])->name("pins.edit");
    Route::put("/pins/{pin}", [
        App\Http\Controllers\PinController::class,
        "update",
    ])->name("pins.update");
    Route::delete("/pins/{pin}", [
        App\Http\Controllers\PinController::class,
        "destroy",
    ])->name("pins.destroy");
    Route::post("/pins/{pin}/like", [
        App\Http\Controllers\PinController::class,
        "toggleLike",
    ])->name("pins.like");

    // Board Routes
    Route::get("/boards", [
        App\Http\Controllers\BoardController::class,
        "index",
    ])->name("boards.index");
    Route::get("/boards/create", [
        App\Http\Controllers\BoardController::class,
        "create",
    ])->name("boards.create");
    Route::post("/boards", [
        App\Http\Controllers\BoardController::class,
        "store",
    ])->name("boards.store");
    Route::get("/boards/{board}", [
        App\Http\Controllers\BoardController::class,
        "show",
    ])->name("boards.show");
    Route::get("/boards/{board}/edit", [
        App\Http\Controllers\BoardController::class,
        "edit",
    ])->name("boards.edit");
    Route::put("/boards/{board}", [
        App\Http\Controllers\BoardController::class,
        "update",
    ])->name("boards.update");
    Route::delete("/boards/{board}", [
        App\Http\Controllers\BoardController::class,
        "destroy",
    ])->name("boards.destroy");

    // AJAX Routes
    Route::get("/api/boards/select", [
        App\Http\Controllers\BoardController::class,
        "getBoardsForSelect",
    ])->name("api.boards.select");
    Route::get("/api/boards/{board}/pins", [
        App\Http\Controllers\PinController::class,
        "getByBoard",
    ])->name("api.boards.pins");
});

// Public Routes
Route::get("/search", [
    App\Http\Controllers\PinController::class,
    "search",
])->name("pins.search");
