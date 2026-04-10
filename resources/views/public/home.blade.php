@extends('layouts.app')

@section('title', 'Assistir Filmes e Séries Online Grátis Dublado - Streaming em HD | FYNECINE')
@section('description', 'Assista aos melhores filmes e séries online grátis no FYNECINE. Catálogo completo com lançamentos dublados e legendados em 1080p e 4K. O melhor do cinema gratuito.')
@section('keywords', 'assistir filmes online, assistir series online, filmes gratis dublado, fynecine online, tv ao vivo gratis')

@section('content')
    <main>
        @if($sliders->isNotEmpty())
            <div class="hero-section">
                <div class="container">
                    <div class="featured-slider">
                        <div class="slides-container" id="featuredSlider">
                            @foreach($sliders as $index => $slider)
                                                    @php
                                                        $content = $slider->content;
                                                        if (!$content)
                                                            continue;
                                                        $title = $slider->title;
                                                        $year = $content->release_year ?? $content->first_air_year;
                                                        $rating = $content->rating;
                                                        $description = $content->overview;
                                                        $type = $slider->content_type === 'movie' ? 'Filme' : 'Série';
                                                        $image = $slider->image_url ?? $content->backdrop_url ?? $content->poster_url;

                                                        if ($image && strpos($image, '/') === 0) {
                                                            // 🔥 TAMANHO CERTO PRO HERO
                                                            $image = 'https://image.tmdb.org/t/p/w1280' . $image;
                                                        }
                                                    @endphp
                                                    <div class="slide {{ $index === 0 ? 'active' : '' }}">

                                                        {{-- IMAGEM --}}
                                                        <img src="{{ $image }}" srcset="
                                    {{ str_replace('w1280', 'w780', $image) }} 780w,
                                    {{ str_replace('w1280', 'w1280', $image) }} 1280w
                                  " sizes="100vw" alt="{{ $title }}" class="slide-bg" width="1280" height="720" decoding="async" @if($index === 0)
                                fetchpriority="high" loading="eager" @else loading="lazy" @endif>

                                                        <div class="slide-info">
                                                            <h2>{{ $title }}</h2>

                                                            <div class="meta">
                                                                <span><i class="fas fa-tag"></i> {{ $type }}</span>
                                                                <span><i class="fas fa-calendar"></i> {{ $year }}</span>
                                                                <span class="rating">
                                                                    <i class="fas fa-star"></i> {{ number_format($rating, 1) }}
                                                                </span>
                                                            </div>

                                                            <p>{{ $description }}</p>

                                                            @php
                                                                $itemUrl = $slider->content_type === 'movie'
                                                                    ? route('movies.show', $content->slug)
                                                                    : route('series.show', $content->slug);
                                                            @endphp

                                                            <a href="{{ $itemUrl }}" class="btn-assistir">
                                                                <i class="fas fa-play"></i> Assistir Agora
                                                            </a>
                                                        </div>

                                                    </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="dots" id="dotsContainer">
                        @foreach($sliders as $index => $slider)
                            <div class="dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div id="sections-container">
            @foreach($sections as $section)
                @php
                    $iconClass = 'fas fa-clapperboard';
                    if ($section->type === 'genre')
                        $iconClass = 'fas fa-theater-masks';
                    if ($section->type === 'recently_added')
                        $iconClass = 'fas fa-fire';
                    if ($section->type === 'upcoming')
                        $iconClass = 'fas fa-calendar-alt';
                    if ($section->type === 'events')
                        $iconClass = 'fas fa-trophy';
                @endphp
                <div class="section">
                    <div class="container section-header">
                        <h2><i class="{{ $iconClass }}"></i> {{ $section->title }}</h2>
                        @php
                            $seeAllUrl = $section->content_type === 'series' ? url('/series') : url('/filmes');
                            if ($section->type === 'genre' && $section->genre) {
                                $seeAllUrl = route('genre.show', $section->genre->slug);
                            }
                        @endphp
                        <a href="{{ $seeAllUrl }}" class="btn-see-all">Ver tudo <i class="fas fa-chevron-right"
                                style="font-size:0.7rem"></i></a>
                    </div>

                    <div class="slider-container">
                        <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>

                        <div class="scroll-wrapper">
                            <div class="cards-row">
                                @foreach($section->items as $item)
                                    @php
                                        $itemTitle = $item->title ?? $item->name;
                                        $itemYear = $item->release_year ?? $item->first_air_year;
                                        $itemRating = $item->rating;
                                        $itemPoster = $item->poster_url ?? $item->poster_path;
                                        if ($itemPoster && strpos($itemPoster, '/') === 0)
                                            $itemPoster = 'https://image.tmdb.org/t/p/w300' . $itemPoster;

                                        $isSeries = ($item->type === 'series' || $item->type === 'serie' || isset($item->number_of_seasons));
                                        $itemType = $isSeries ? 'SÉRIE' : 'FILME';
                                        $itemIcon = $isSeries ? 'fas fa-tv' : 'fas fa-film';

                                        $itemUrl = 'javascript:void(0)';
                                        if ($item->slug) {
                                            $itemUrl = $isSeries ? route('series.show', $item->slug) : route('movies.show', $item->slug);
                                        }
                                    @endphp
                                    <a href="{{ $itemUrl }}" class="card">

                                        <div class="card-img-wrapper">

                                            @if($itemPoster)
                                                <img src="{{ $itemPoster }}" srcset="{{ str_replace('w300', 'w185', $itemPoster) }} 185w, {{ str_replace('w300', 'w300', $itemPoster) }} 300w,{{ str_replace('w300', 'w500', $itemPoster) }} 500w" sizes="(max-width: 640px) 140px, 200px" alt="{{ $itemTitle }}" class="card-img" loading="lazy" decoding="async" width="300" height="450">
                                            @else
                                                <div class="card-img placeholder">
                                                    <i class="{{ $itemIcon }} placeholder-icon"></i>
                                                </div>
                                            @endif

                                            <div class="card-badge">{{ $itemType }}</div>

                                            <div class="card-overlay">
                                                <div class="play-circle">
                                                    <i class="fas fa-play"></i>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="card-info">
                                            <h4>{{ $itemTitle }}</h4>

                                            <div class="card-meta">
                                                <span>{{ $itemYear }}</span>
                                                <span class="rating">
                                                    <i class="fas fa-star"></i>{{ number_format($itemRating, 1) }}
                                                </span>
                                            </div>
                                        </div>

                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection