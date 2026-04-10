<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenreController extends Controller
{
    public function show($slug)
    {
        $genre = Genre::where('slug', $slug)->firstOrFail();

        // 1. Query para Filmes deste Gênero
        $moviesQuery = DB::table('movies')
            ->join('genre_movie', 'movies.id', '=', 'genre_movie.movie_id')
            ->where('genre_movie.genre_id', $genre->id)
            ->select(
                'movies.id', 
                'movies.title as name', 
                'movies.slug', 
                'movies.poster_path', 
                'movies.rating', 
                'movies.created_at', 
                DB::raw("'movie' as type"), 
                'movies.release_year as year'
            );

        // 2. Query para Séries deste Gênero unida via UNION
        $results = DB::table('series')
            ->join('genre_series', 'series.id', '=', 'genre_series.series_id')
            ->where('genre_series.genre_id', $genre->id)
            ->select(
                'series.id', 
                'series.name', 
                'series.slug', 
                'series.poster_path', 
                'series.rating', 
                'series.created_at', 
                DB::raw("'series' as type"), 
                'series.first_air_year as year'
            )
            ->union($moviesQuery)
            ->orderByDesc('created_at')
            ->paginate(28)
            ->withQueryString();

        return view('public.genre', compact('results', 'genre'));
    }
}
