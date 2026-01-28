<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = DB::table('posts')
            ->leftJoin('categories', 'posts.categoryId', '=', 'categories.id')
            ->select('posts.*', 'categories.name as category_name')
            ->orderBy('posts.created_at', 'desc') // Pastikan 'A' besar jika di DB memang begitu
            ->get();

        return view('index', compact('posts'));
    }
}