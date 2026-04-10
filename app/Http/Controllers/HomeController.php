<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\HomeSection;
use App\Models\Movie;
use App\Models\Serie;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('active', true)
            ->whereNull('content_category_id')
            ->orderBy('position')
            ->get();

        $sections = HomeSection::where('is_active', true)
            ->with('genre')
            ->whereNull('content_category_id')
            ->whereNotIn('type', ['trending', 'network', 'networks', 'top_10'])
            ->orderBy('order')
            ->get()
            ->map(function ($section) {
                $section->items = $section->resolveItems();
                return $section;
            })
            ->filter(function ($section) {
                return $section->items->isNotEmpty();
            });

        return view('public.home', compact('sliders', 'sections'));
    }
}
