<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        
        if (empty($q)) {
            return redirect()->to('/');
        }

        // 1. Query para Filmes
        $moviesQuery = DB::table('movies')
            ->select(
                'id', 
                'title as name', 
                'slug', 
                'poster_path', 
                'rating', 
                'created_at', 
                DB::raw("'movie' as type"), 
                'release_year as year'
            )
            ->where('title', 'LIKE', "%$q%");

        // 2. Query para Séries unida com Filmes via UNION
        $results = DB::table('series')
            ->select(
                'id', 
                'name', 
                'slug', 
                'poster_path', 
                'rating', 
                'created_at', 
                DB::raw("'series' as type"), 
                'first_air_year as year'
            )
            ->where('name', 'LIKE', "%$q%")
            ->union($moviesQuery)
            ->orderByDesc('created_at')
            ->paginate(28)
            ->withQueryString();

        return view('public.search', compact('results', 'q'));
    }
}
