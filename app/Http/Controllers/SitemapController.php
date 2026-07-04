<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $movies = Movie::select('slug', 'updated_at')->orderBy('updated_at', 'desc')->get();
        $series = Serie::select('slug', 'updated_at')->orderBy('updated_at', 'desc')->get();

        $content = view('frontend.sitemap', compact('movies', 'series'))->render();

        return response($content, 200)->header('Content-Type', 'text/xml; charset=UTF-8');
    }
}
