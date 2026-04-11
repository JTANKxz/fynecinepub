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

                                                        if ($image) {
                                                            if (strpos($image, 'image.tmdb.org') !== false) {
                                                                $image = str_replace(['original', 'w500', 'w300'], 'w1280', $image);
                                                            } elseif (strpos($image, '/') === 0) {
                                                                $image = 'https://image.tmdb.org/t/p/w1280' . $image;
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="slide {{ $index === 0 ? 'active' : '' }}">

                                                        {{-- IMAGEM --}}
                                                        @php
                                                            $sliderSrc = "https://images.weserv.nl/?url=" . urlencode($image) . "&w=1280&output=webp&q=70";
                                                            $sliderSet = "https://images.weserv.nl/?url=" . urlencode($image) . "&w=700&output=webp&q=70 700w, " . $sliderSrc . " 1280w";
                                                        @endphp
                                                        <img src="{{ $sliderSrc }}" srcset="{{ $sliderSet }}" sizes="100vw" alt="{{ $title }}" class="slide-bg" width="1280" height="720" decoding="async" @if($index === 0)
                                                                fetchpriority="high" loading="eager" @else loading="lazy" @endif>

                                                        <div class="slide-info">
                                                            <h2>{{ $title }}</h2>

                                                            <div class="meta">
                                                                <span><i data-lucide="tag"></i> {{ $type }}</span>
                                                                <span><i data-lucide="calendar"></i> {{ $year }}</span>
                                                                <span class="rating">
                                                                    <i data-lucide="star"></i> {{ number_format($rating, 1) }}
                                                                </span>
                                                            </div>

                                                            <p>{{ $description }}</p>

                                                            @php
                                                                $itemUrl = $slider->content_type === 'movie'
                                                                    ? route('movies.show', $content->slug)
                                                                    : route('series.show', $content->slug);
                                                            @endphp

                                                            <a href="{{ $itemUrl }}" class="btn-assistir">
                                                                <i data-lucide="play"></i> Assistir Agora
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
                    $icon = 'clapperboard';
                    if ($section->type === 'genre')
                        $icon = 'drama'; // Lucide 'drama' is a good fit for theater-masks
                    if ($section->type === 'recently_added')
                        $icon = 'flame';
                    if ($section->type === 'upcoming')
                        $icon = 'calendar-days';
                    if ($section->type === 'events')
                        $icon = 'trophy';
                @endphp
                <div class="section">
                    <div class="container section-header">
                        <h2><i data-lucide="{{ $icon }}"></i> {{ $section->title }}</h2>
                        @php
                            $seeAllUrl = $section->content_type === 'series' ? url('/series') : url('/filmes');
                            if ($section->type === 'genre' && $section->genre) {
                                $seeAllUrl = route('genre.show', $section->genre->slug);
                            }
                        @endphp
                        <a href="{{ $seeAllUrl }}" class="btn-see-all">Ver tudo <i data-lucide="chevron-right"
                                                                style="width:12px;height:12px"></i></a>
                    </div>

                    <div class="slider-container">
                        <button class="slider-btn prev-btn"><i data-lucide="chevron-left"></i></button>

                        <div class="scroll-wrapper">
                            <div class="cards-row">
                                @foreach($section->items as $item)
                                    @php
                                        $itemTitle = $item->title ?? $item->name;
                                        $itemYear = $item->release_year ?? $item->first_air_year;
                                        $itemRating = $item->rating;
                                        $itemPoster = $item->poster_url ?? $item->poster_path;
                                        if ($itemPoster) {
                                            if (strpos($itemPoster, 'image.tmdb.org') !== false) {
                                                $itemPoster = str_replace(['original', 'w500', 'w300'], 'w342', $itemPoster);
                                            } elseif (strpos($itemPoster, '/') === 0) {
                                                $itemPoster = 'https://image.tmdb.org/t/p/w342' . $itemPoster;
                                            }
                                        }

                                        $isSeries = ($item->type === 'series' || $item->type === 'serie' || isset($item->number_of_seasons));
                                        $itemType = $isSeries ? 'SÉRIE' : 'FILME';
                                        $itemIcon = $isSeries ? 'tv' : 'film';

                                        $itemUrl = 'javascript:void(0)';
                                        if ($item->slug) {
                                            $itemUrl = $isSeries ? route('series.show', $item->slug) : route('movies.show', $item->slug);
                                        }
                                    @endphp
                                    <a href="{{ $itemUrl }}" class="card">

                                        <div class="card-img-wrapper">

                                            @if($itemPoster)
                                                @php
                                                    $cardSrc = "https://images.weserv.nl/?url=" . urlencode($itemPoster) . "&w=250&output=webp&q=70";
                                                    $cardSet = "https://images.weserv.nl/?url=" . urlencode($itemPoster) . "&w=180&output=webp&q=70 180w, " . $cardSrc . " 250w, https://images.weserv.nl/?url=" . urlencode($itemPoster) . "&w=342&output=webp&q=70 342w, https://images.weserv.nl/?url=" . urlencode($itemPoster) . "&w=500&output=webp&q=70 500w";
                                                @endphp
                                                <img src="{{ $cardSrc }}" 
                                                     srcset="{{ $cardSet }}" 
                                                     sizes="(max-width: 640px) 140px, 200px" 
                                                     alt="{{ $itemTitle }}" class="card-img" loading="lazy" decoding="async" width="300" height="450">
                                            @else
                                                <div class="card-img placeholder">
                                                    <i data-lucide="{{ $itemIcon }}" class="placeholder-icon"></i>
                                                </div>
                                            @endif

                                            <div class="card-badge">{{ $itemType }}</div>

                                            <div class="card-overlay">
                                                <div class="play-circle">
                                                    <i data-lucide="play"></i>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="card-info">
                                            <h4>{{ $itemTitle }}</h4>

                                            <div class="card-meta">
                                                <span>{{ $itemYear }}</span>
                                                <span class="rating">
                                                    <i data-lucide="star"></i>{{ number_format($itemRating, 1) }}
                                                </span>
                                            </div>
                                        </div>

                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <button class="slider-btn next-btn"><i data-lucide="chevron-right"></i></button>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection