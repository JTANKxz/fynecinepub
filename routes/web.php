<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GenreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/filmes', [MovieController::class, 'index']);
Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
Route::get('/pesquisa', [SearchController::class, 'index'])->name('search');
Route::get('/genero/{slug}', [GenreController::class, 'show'])->name('genre.show');

Route::get('/filmes/{slug}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/series/{slug}', [SeriesController::class, 'show'])->name('series.show');
use App\Http\Controllers\SitemapController;
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::get('/inicio', function () { return view('public.landing'); })->name('landing');
