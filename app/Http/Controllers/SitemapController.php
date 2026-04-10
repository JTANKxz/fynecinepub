<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $movies = Movie::select('slug', 'updated_at')->latest()->get();
        $series = Serie::select('slug', 'updated_at')->latest()->get();

        $content = view('public.sitemap', [
            'movies' => $movies,
            'series' => $series,
        ])->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
