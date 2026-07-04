<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Public\PublicHomeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SitemapController;

// Rotas Frontend (Públicas)
Route::get('/', [PublicHomeController::class, 'index'])->name('home');

Route::controller(FrontendController::class)->group(function () {
    Route::get('/filme/{slug}', 'movie')->name('frontend.movie');
    Route::get('/serie/{slug}', 'serie')->name('frontend.serie');
    Route::get('/serie/{slug}/temporada/{season}/episodio/{episode}', 'episode')->name('frontend.episode');
    Route::get('/busca', 'search')->name('frontend.search');
    Route::get('/genero/{slug}', 'genre')->name('frontend.genre');
    Route::get('/estudio/{slug}', 'network')->name('frontend.network');
    Route::get('/assistir/{slug}', 'player')->name('frontend.player');
    Route::get('/baixar-app', 'appDownload')->name('frontend.app-download');
});

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Rotas Públicas (Legal)
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
