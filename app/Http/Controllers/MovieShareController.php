<?php

namespace App\Http\Controllers;
use App\Models\Movies;

use Illuminate\Http\Request;

class MovieShareController extends Controller
{
    public function show($id)
    {
        $movie = Movies::findOrFail($id);
        return view('movies.share', compact('movie'));
    }
}
