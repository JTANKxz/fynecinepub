<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use App\Models\Genre;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $query = Serie::query();

        // Filtro por Gênero
        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genres.id', $request->genre)
                  ->orWhere('genres.slug', $request->genre);
            });
        }

        // Filtro por Ano (first_air_year para séries)
        if ($request->filled('year')) {
            $query->where('first_air_year', $request->year);
        }

        // Filtro por Pesquisa
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // Ordenação
        switch ($request->get('sort')) {
            case 'populares':
            case 'avaliacao':
                $query->orderByDesc('rating');
                break;
            case 'antigos':
                $query->orderBy('first_air_year');
                break;
            case 'recentes':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $series = $query->paginate(28)->withQueryString();
        
        // Dados para os filtros
        $genres = Genre::orderBy('name')->get();

        $years = Serie::select('first_air_year')
            ->whereNotNull('first_air_year')
            ->distinct()
            ->orderByDesc('first_air_year')
            ->pluck('first_air_year');

        return view('public.series', compact('series', 'genres', 'years'));
    }

    public function show(Request $request, $slug)
    {
        // Busca por slug ou por TMDB ID
        $serie = Serie::where('slug', $slug)
            ->orWhere('tmdb_id', $slug)
            ->with(['genres', 'cast', 'seasons' => function($q) {
                $q->orderBy('season_number');
            }])
            ->firstOrFail();

        // Determinar temporada selecionada (default para a primeira)
        $selectedSeasonNumber = $request->get('season', 1);
        
        $selectedSeason = $serie->seasons()
            ->where('season_number', $selectedSeasonNumber)
            ->first();

        // Se a temporada não existir, pega a primeira disponível
        if (!$selectedSeason && $serie->seasons->isNotEmpty()) {
            $selectedSeason = $serie->seasons->first();
            $selectedSeasonNumber = $selectedSeason->season_number;
        }

        // Carregar episódios da temporada selecionada com links de AutoEmbed
        $episodes = collect();
        if ($selectedSeason) {
            $episodes = $selectedSeason->episodes()
                ->orderBy('episode_number')
                ->get()
                ->map(function ($episode) use ($serie, $selectedSeasonNumber) {
                    // Gera links de AutoEmbed dinamicamente (Ignora banco)
                    $episode->linksData = [
                        [
                            'name' => 'Player 1 (Principal)',
                            'url' => 'https://myembed.biz/serie/' . $serie->tmdb_id . '/' . $selectedSeasonNumber . '/' . $episode->episode_number,
                            'type' => 'embed'
                        ],
                        [
                            'name' => 'Player 2 (Superflix)',
                            'url' => 'https://superflixapi.rest/serie/' . $serie->tmdb_id . '/' . $selectedSeasonNumber . '/' . $episode->episode_number,
                            'type' => 'embed'
                        ]
                    ];
                    return $episode;
                });
        }

        // Recomendações: Mesmos gêneros
        $firstGenre = $serie->genres->first();
        $similarSeries = collect();
        if ($firstGenre) {
            $similarSeries = Serie::whereHas('genres', function ($query) use ($firstGenre) {
                $query->where('genres.id', $firstGenre->id);
            })
            ->where('id', '!=', $serie->id)
            ->take(12)
            ->get();
        }

        $firstEpLinks = $episodes->isNotEmpty() ? $episodes->first()->linksData : [];

        return view('public.series.show', compact('serie', 'episodes', 'selectedSeasonNumber', 'similarSeries', 'firstEpLinks'));
    }
}
