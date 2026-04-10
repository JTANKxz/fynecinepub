<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();

        // Filtro por Gênero
        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genres.id', $request->genre)
                  ->orWhere('genres.slug', $request->genre);
            });
        }

        // Filtro por Ano
        if ($request->filled('year')) {
            $query->where('release_year', $request->year);
        }

        // Filtro por Pesquisa (opcional, caso queira integrar com o header)
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // Ordenação
        switch ($request->get('sort')) {
            case 'populares':
            case 'avaliacao':
                $query->orderByDesc('rating');
                break;
            case 'antigos':
                $query->orderBy('release_year');
                break;
            case 'recentes':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $movies = $query->paginate(28)->withQueryString();
        
        // Dados para os filtros
        $genres = Genre::orderBy('name')->get();
        $years = Movie::select('release_year')
            ->whereNotNull('release_year')
            ->distinct()
            ->orderByDesc('release_year')
            ->pluck('release_year');

        return view('public.movies', compact('movies', 'genres', 'years'));
    }

    public function show($slug)
    {
        // Busca por slug ou por TMDB ID
        $movie = Movie::where('slug', $slug)
            ->orWhere('tmdb_id', $slug)
            ->with(['genres', 'cast'])
            ->firstOrFail();

        // Configuração de AutoEmbed (Desativa links do banco por enquanto)
        $playLinks = collect([
            (object)[
                'name' => 'Player 1 (Principal)',
                'url' => 'https://myembed.biz/filme/' . $movie->tmdb_id,
                'type' => 'embed'
            ],
            (object)[
                'name' => 'Player 2 (Superflix)',
                'url' => 'https://superflixapi.rest/filme/' . $movie->tmdb_id,
                'type' => 'embed'
            ]
        ]);

        // Recomendações: Mesmos gêneros
        $firstGenre = $movie->genres->first();
        $similarMovies = collect();
        if ($firstGenre) {
            $similarMovies = Movie::whereHas('genres', function ($query) use ($firstGenre) {
                $query->where('genres.id', $firstGenre->id);
            })
            ->where('id', '!=', $movie->id)
            ->take(12)
            ->get();
        }

        return view('public.movies.show', compact('movie', 'similarMovies', 'playLinks'));
    }
}
