<?php
$meta = [
    'seo_title' => $movie->title . ' - ' . env('APP_NAME'),
    'seo_description' => !empty($movie->content) ? strip_tags($movie->content) : 'Watch ' . $movie->title . ' on ' . env('APP_NAME'),
];
$ogimage = !empty($movie->image) ? $movie->image->url : '';
$ogtype = 'video.movie';
?>

@extends('layouts.frontend')

@section('content')
<div style="text-align:center; padding:60px 20px;">
    <img src="{{ $ogimage }}" alt="{{ $movie->title }}" style="max-width:300px; border-radius:8px;">
    <h1>{{ $movie->title }}</h1>
    <p>{{ strip_tags($movie->content) }}</p>
    <a href="{{ url('/watch/' . $movie->id) }}" class="btn active">Watch Now on BESTCAST</a>
</div>
@endsection