@extends('layouts.app')

@section('title', $movie->title)

@php
    $backdrop = $movie->backdrop_path;
    if ($backdrop) {
        if (strpos($backdrop, 'image.tmdb.org') !== false) {
            $backdrop = str_replace('original', 'w1280', $backdrop);
        } elseif (strpos($backdrop, '/') === 0) {
            $backdrop = 'https://image.tmdb.org/t/p/w1280' . $backdrop;
        }
    }
    $backdropFallback = $movie->poster_path;
    if ($backdropFallback) {
        if (strpos($backdropFallback, 'image.tmdb.org') !== false) {
            $backdropFallback = str_replace('original', 'w1280', $backdropFallback);
        } elseif (strpos($backdropFallback, '/') === 0) {
            $backdropFallback = 'https://image.tmdb.org/t/p/w1280' . $backdropFallback;
        }
    }

    // AGGRESSIVE SEO TITLE & DESCRIPTION
    $seoTitle = "Assistir " . $movie->title . " Online Grátis Dublado e Legendado HD | FYNECINE";
    $seoDesc = "Assistir o filme " . $movie->title . " (" . $movie->release_year . ") grátis online em Full HD. Sinopse: " . Str::limit($movie->overview, 140) . ". O melhor do cinema gratuito.";
    $ogImage = $backdrop ?? $backdropFallback;

    // Formatação de duração
    $runtime = $movie->runtime;
    $formattedRuntime = '';
    if ($runtime) {
        $hours = floor($runtime / 60);
        $minutes = $runtime % 60;
        $formattedRuntime = ($hours > 0 ? $hours . 'h ' : '') . ($minutes > 0 ? $minutes . 'm' : '');
    }
@endphp

@section('title', $seoTitle)
@section('description', $seoDesc)
@section('og_image', $ogImage)
@section('og_type', 'video.movie')

@section('content')
    {{-- BACKDROP IMERSIVO E ELEMENTOS EXTRAÍDOS PARA FUGIR DE BUGS DE Z-INDEX --}}

    @push('seo')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Movie",
      "name": "{{ $movie->title }}",
      "description": "{{ addslashes($movie->overview) }}",
      "image": "{{ $ogImage }}",
      "datePublished": "{{ $movie->release_year }}",
      "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ $movie->rating }}",
        "bestRating": "10",
        "worstRating": "1",
        "ratingCount": "100"
      },
      "genre": [
        @foreach($movie->genres as $genre)"{{ $genre->name }}"@if(!$loop->last),@endif @endforeach
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "BreadcrumbList",
      "itemListElement": [{
        "@@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "{{ url('/') }}"
      },{
        "@@type": "ListItem",
        "position": 2,
        "name": "Filmes",
        "item": "{{ url('/filmes') }}"
      },{
        "@@type": "ListItem",
        "position": 3,
        "name": "{{ $movie->title }}",
        "item": "{{ url()->current() }}"
      }]
    }
    </script>
    @endpush

    @php
        $optimizedBackdrop = "https://images.weserv.nl/?url=" . urlencode($backdrop ?? $backdropFallback) . "&w=1280&output=webp&q=80";
    @endphp
    <div class="backdrop-container" id="backdropContainer"
        style="background-image: url('{{ $optimizedBackdrop }}');">
        <div class="backdrop-overlay"></div>
    </div>

    {{-- Botão central mobile --}}
    <button class="mobile-backdrop-play trigger-modal-play" aria-label="Assistir Agora">
        <i data-lucide="play"></i>
    </button>

    {{-- Container do Player --}}
    <div class="player-wrapper" id="playerWrapper">
        <button class="close-player-btn" id="closePlayerBtn">
            <i data-lucide="arrow-left"></i> Voltar
        </button>

        <video id="videoPlayer" playsinline controls style="display: none;"
            data-poster="{{ $backdrop ?? $backdropFallback }}"></video>

        <iframe id="iframePlayer" src="" allowfullscreen style="display: none;"></iframe>
    </div>

    <main class="details-page">
        <div class="container">
            {{-- PÔSTER E INFORMAÇÕES PRINCIPAIS --}}
            <div class="details-header-content">
                <div class="poster-wrapper">
                    @php
                        $poster = $movie->poster_path;
                        if ($poster) {
                            if (strpos($poster, 'image.tmdb.org') !== false) {
                                $poster = str_replace('original', 'w500', $poster);
                            } elseif (strpos($poster, '/') === 0) {
                                $poster = 'https://image.tmdb.org/t/p/w500' . $poster;
                            }
                        }
                    @endphp
                    @php
                        $optimizedPoster = "https://images.weserv.nl/?url=" . urlencode($poster ?? 'https://placehold.co/400x600/18181b/8b5cf6?text=Sem+Poster') . "&w=500&output=webp&q=80";
                    @endphp
                    <img src="{{ $optimizedPoster }}"
                        alt="Assistir {{ $movie->title }} Online Grátis HD" fetchpriority="high">
                </div>

                <div class="info-wrapper">
                    <h1 class="details-title">{{ $movie->title }}</h1>

                    <div class="details-meta">
                        <span><i data-lucide="calendar"></i> {{ $movie->release_year }}</span>
                        @if ($runtime)
                            <span><i data-lucide="clock"></i> {{ $formattedRuntime }}</span>
                        @endif
                        <span class="rating"><i data-lucide="star"></i> {{ number_format($movie->rating, 1) }}/10</span>
                        @if ($movie->age_rating)
                            <span class="age-badge"><i data-lucide="eye"></i> +{{ $movie->age_rating }}</span>
                        @endif
                    </div>

                    <div class="details-genres">
                        @foreach ($movie->genres as $genre)
                            <a href="{{ route('genre.show', $genre->slug) }}" class="genre-badge">{{ $genre->name }}</a>
                        @endforeach
                    </div>

                    <p class="details-synopsis">
                        {{ $movie->overview }}
                    </p>

                    <div class="details-actions">
                        <button class="btn-primary trigger-modal-play">
                            <i data-lucide="play"></i> Assistir Agora
                        </button>
                        @if ($movie->trailer_url)
                            <a href="{{ $movie->trailer_url }}" target="_blank" class="btn-secondary">
                                <i data-lucide="film"></i> Ver Trailer
                            </a>
                        @elseif($movie->trailer_key)
                            <a href="https://www.youtube.com/watch?v={{ $movie->trailer_key }}" target="_blank"
                                class="btn-secondary">
                                <i data-lucide="film"></i> Ver Trailer
                            </a>
                        @endif
                        <button class="btn-secondary" style="padding: 12px 18px;" aria-label="Adicionar aos favoritos">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO DE ELENCO --}}
            @if ($movie->cast->isNotEmpty())
                <section class="details-section">
                    <div class="section-header">
                        <h2><i data-lucide="users"></i> Elenco Principal</h2>
                    </div>

                    <div class="slider-container">
                        <button class="slider-btn prev-btn"><i data-lucide="chevron-left"></i></button>
                        <div class="scroll-wrapper">
                            <div class="cards-row">
                                @foreach ($movie->cast->sortBy('pivot.order') as $actor)
                                    @php
                                        $profile = $actor->profile_path;
                                        if ($profile) {
                                            if (strpos($profile, 'image.tmdb.org') !== false) {
                                                $profile = str_replace(['original', 'w500'], 'w185', $profile);
                                            } elseif (strpos($profile, '/') === 0) {
                                                $profile = 'https://image.tmdb.org/t/p/w185' . $profile;
                                            }
                                        }
                                        $avatarFallback =
                                            'https://www.themoviedb.org/assets/2/v4/glyphicons/basic/glyphicons-basic-4-user-grey-d8fe57731f22442a9c18fb27a971256745c06bbc50776791fe2177a6c0f04adc.svg';
                                    @endphp
                                    <div class="cast-card">
                                        <div class="cast-avatar">
                                            @php
                                                $castUrl = $profile ?? $avatarFallback;
                                                $optimizedCast = "https://images.weserv.nl/?url=" . urlencode($castUrl) . "&w=185&h=230&fit=cover&output=webp&q=80";
                                            @endphp
                                            <img src="{{ $optimizedCast }}" alt="{{ $actor->name }}" loading="lazy">
                                        </div>
                                        <h5 class="cast-name">{{ $actor->name }}</h5>
                                        <span class="cast-role">{{ $actor->pivot->character }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button class="slider-btn next-btn"><i data-lucide="chevron-right"></i></button>
                    </div>
                </section>
            @endif

            {{-- SEÇÃO DE RECOMENDADOS --}}
            @if ($similarMovies->isNotEmpty())
                <section class="details-section">
                    <div class="section-header">
                        <h2><i data-lucide="layers"></i> Títulos Semelhantes</h2>
                    </div>

                    <div class="slider-container">
                        <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                        <div class="scroll-wrapper">
                            <div class="cards-row">
                                @foreach ($similarMovies as $similar)
                                    @php
                                        $sPoster = $similar->poster_path;
                                        if ($sPoster) {
                                            if (strpos($sPoster, 'image.tmdb.org') !== false) {
                                                $sPoster = str_replace(['original', 'w500', 'w300'], 'w342', $sPoster);
                                            } elseif (strpos($sPoster, '/') === 0) {
                                                $sPoster = 'https://image.tmdb.org/t/p/w342' . $sPoster;
                                            }
                                        }
                                        $itemUrl = $similar->slug ? route('movies.show', $similar->slug) : 'javascript:void(0)';
                                    @endphp
                                    <a href="{{ $itemUrl }}" class="card">
                                        <div class="card-img-wrapper">
                                            @if($sPoster)
                                                @php
                                                    $sCardSrc = "https://images.weserv.nl/?url=" . urlencode($sPoster) . "&w=342&output=webp&q=80";
                                                    $sCardSet = "https://images.weserv.nl/?url=" . urlencode($sPoster) . "&w=185&output=webp&q=80 185w, " . $sCardSrc . " 342w, https://images.weserv.nl/?url=" . urlencode($sPoster) . "&w=500&output=webp&q=80 500w";
                                                @endphp
                                                <img src="{{ $sCardSrc }}" 
                                                     srcset="{{ $sCardSet }}" 
                                                     sizes="(max-width: 640px) 140px, 200px" 
                                                     alt="{{ $similar->title }}" class="card-img" loading="lazy" decoding="async" width="300" height="450">
                                            @else
                                                <div class="card-img placeholder">
                                                    <i data-lucide="film" class="placeholder-icon"></i>
                                                </div>
                                            @endif
                                            <div class="card-badge">FILME</div>
                                            <div class="card-overlay">
                                                <div class="play-circle"><i data-lucide="play"></i></div>
                                            </div>
                                        </div>
                                        <div class="card-info">
                                            <h4>{{ $similar->title }}</h4>
                                            <div class="card-meta">
                                                <span>{{ $similar->release_year }}</span>
                                                <span class="rating"><i
                                                        data-lucide="star"></i>{{ number_format($similar->rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </section>
            @endif
        </div>
    </main>

    {{-- MODAL DE OPÇÕES DE PLAYER --}}
    <div class="modal-overlay" id="optionsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Escolha um Servidor</h3>
                <button class="close-modal-btn" id="closeModalBtn"><i data-lucide="x"></i></button>
            </div>
            <div class="modal-options-list">
                @forelse($playLinks as $link)
                    <button class="option-item select-server" data-src="{{ $link->url }}"
                        data-type="{{ $link->type }}">
                        <i data-lucide="server"></i>
                        <div>
                            <div style="font-weight: 700;">{{ $link->name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                Alta Velocidade • {{ Str::upper($link->type) }}
                            </div>
                        </div>
                    </button>
                @empty
                    <div style="text-align: center; color: var(--text-muted); padding: 20px;">
                        Nenhum servidor disponível no momento.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Plyr e HLS.js --}}
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
@endsection