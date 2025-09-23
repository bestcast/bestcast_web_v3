<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrailerController extends Controller
{
    public function showTrailer($id)
    {
        $movie = \App\Models\Movies::findOrFail($id);

        return view('trailer.trailer', compact('movie'));
    }

}