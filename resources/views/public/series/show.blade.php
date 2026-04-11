@extends('layouts.app')

@section('title', $serie->name)

@php
    $backdrop = $serie->backdrop_path;
    if ($backdrop) {
        if (strpos($backdrop, 'image.tmdb.org') !== false) {
            $backdrop = str_replace('original', 'w1280', $backdrop);
        } elseif (strpos($backdrop, '/') === 0) {
            $backdrop = 'https://image.tmdb.org/t/p/w1280' . $backdrop;
        }
    }
    $backdropFallback = $serie->poster_path;
    if ($backdropFallback) {
        if (strpos($backdropFallback, 'image.tmdb.org') !== false) {
            $backdropFallback = str_replace('original', 'w1280', $backdropFallback);
        } elseif (strpos($backdropFallback, '/') === 0) {
            $backdropFallback = 'https://image.tmdb.org/t/p/w1280' . $backdropFallback;
        }
    }

    // AGGRESSIVE SEO TITLE & DESCRIPTION
    $seoTitle = "Assistir " . $serie->name . " Online Grátis - Todas as Temporadas (Dublado) | FYNECINE";
    $seoDesc = "Assistir a série " . $serie->name . " online grátis dublada e legendada. Assista todas as temporadas e episódios em Full HD. Sinopse: " . Str::limit($serie->overview, 140);
    $ogImage = $backdrop ?? $backdropFallback;
@endphp

@section('title', $seoTitle)
@section('description', $seoDesc)
@section('og_image', $ogImage)
@section('og_type', 'video.tv_show')

@section('content')

    @push('seo')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "TVSeries",
      "name": "{{ $serie->name }}",
      "description": "{{ addslashes($serie->overview) }}",
      "image": "{{ $ogImage }}",
      "startDate": "{{ $serie->first_air_year }}",
      "numberOfSeasons": "{{ $serie->seasons->count() }}",
      "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ $serie->rating }}",
        "bestRating": "10",
        "worstRating": "1",
        "ratingCount": "100"
      },
      "genre": [
        @foreach($serie->genres as $genre)"{{ $genre->name }}"@if(!$loop->last),@endif @endforeach
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
        "name": "Séries",
        "item": "{{ url('/series') }}"
      },{
        "@@type": "ListItem",
        "position": 3,
        "name": "{{ $serie->name }}",
        "item": "{{ url()->current() }}"
      }]
    }
    </script>
    @endpush

    @php
        $optimizedBackdrop = "https://images.weserv.nl/?url=" . urlencode($backdrop ?? $backdropFallback) . "&w=1280&output=webp&q=70";
    @endphp
    <div class="backdrop-container" id="backdropContainer"
        style="background-image: url('{{ $optimizedBackdrop }}');">
        <div class="backdrop-overlay"></div>
    </div>

    {{-- Botão central mobile (Toca o primeiro episódio) --}}
    @if($episodes->isNotEmpty())
    @php
        $firstEpLinks = $episodes->first()->links->map(function($l) {
            return ['name' => $l->name, 'url' => $l->url, 'type' => $l->type, 'quality' => $l->quality];
        });
    @endphp
    <button class="mobile-backdrop-play trigger-modal-play" 
            data-episode-id="{{ $episodes->first()->id }}"
            data-links='@json($firstEpLinks)'
            aria-label="Assistir Agora">
        <i data-lucide="play"></i>
    </button>
    @endif

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
                        $poster = $serie->poster_path;
                        if ($poster) {
                            if (strpos($poster, 'image.tmdb.org') !== false) {
                                $poster = str_replace('original', 'w500', $poster);
                            } elseif (strpos($poster, '/') === 0) {
                                $poster = 'https://image.tmdb.org/t/p/w500' . $poster;
                            }
                        }
                    @endphp
                    @php
                        $optimizedPoster = "https://images.weserv.nl/?url=" . urlencode($poster ?? 'https://placehold.co/400x600/18181b/8b5cf6?text=Sem+Poster') . "&w=500&output=webp&q=70";
                    @endphp
                    <img src="{{ $optimizedPoster }}"
                        alt="Assistir Série {{ $serie->name }} Online Grátis HD" fetchpriority="high">
                </div>

                <div class="info-wrapper">
                    <h1 class="details-title">{{ $serie->name }}</h1>

                    <div class="details-meta">
                        <span><i data-lucide="calendar"></i> {{ $serie->first_air_year }} {{ $serie->last_air_year ? '- ' . $serie->last_air_year : '- Presente' }}</span>
                        <span><i data-lucide="layers"></i> {{ $serie->seasons->count() }} Temporadas</span>
                        <span class="rating"><i data-lucide="star"></i> {{ number_format($serie->rating, 1) }}/10</span>
                        @if ($serie->age_rating)
                            <span class="age-badge"><i data-lucide="eye"></i> +{{ $serie->age_rating }}</span>
                        @endif
                    </div>

                    <div class="details-genres">
                        @foreach ($serie->genres as $genre)
                            <a href="{{ route('genre.show', $genre->slug) }}" class="genre-badge">{{ $genre->name }}</a>
                        @endforeach
                    </div>

                    <p class="details-synopsis">
                        {{ $serie->overview }}
                    </p>

                    <div class="details-actions">
                        @if($episodes->isNotEmpty())
                        <button class="btn-primary trigger-modal-play" 
                                data-episode-id="{{ $episodes->first()->id }}"
                                data-links='@json($firstEpLinks)'>
                            <i data-lucide="play"></i> Assistir S{{ $selectedSeasonNumber }}:E1
                        </button>
                        @endif
                        
                        @if ($serie->trailer_url)
                            <a href="{{ $serie->trailer_url }}" target="_blank" class="btn-secondary">
                                <i data-lucide="film"></i> Ver Trailer
                            </a>
                        @elseif($serie->trailer_key)
                            <a href="https://www.youtube.com/watch?v={{ $serie->trailer_key }}" target="_blank"
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

            {{-- SEÇÃO DE EPISÓDIOS --}}
            <section class="episodes-section">
                <div class="episodes-header">
                    <h2><i data-lucide="list-ordered"></i> Episódios</h2>
                    <select class="season-select" id="seasonSelect" aria-label="Escolher Temporada">
                        @foreach($serie->seasons as $season)
                            <option value="{{ $season->season_number }}" {{ $selectedSeasonNumber == $season->season_number ? 'selected' : '' }}>
                                Temporada {{ $season->season_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="episodes-list">
                    @forelse($episodes as $episode)
                        @php
                            $still = $episode->still_path;
                            if ($still) {
                                if (strpos($still, 'image.tmdb.org') !== false) {
                                    $still = str_replace(['original', 'w500'], 'w300', $still);
                                } elseif (strpos($still, '/') === 0) {
                                    $still = 'https://image.tmdb.org/t/p/w300' . $still;
                                }
                            }
                        @endphp
                        <div class="episode-item trigger-modal-play" 
                             data-episode-id="{{ $episode->id }}"
                             data-links='@json($episode->linksData)'>
                            <div class="episode-thumb">
                                @php
                                    $optimizedStill = "https://images.weserv.nl/?url=" . urlencode($still ?? 'https://placehold.co/320x180/18181b/8b5cf6?text=Episódio+'.$episode->episode_number) . "&w=300&h=170&fit=cover&output=webp&q=70";
                                @endphp
                                <img src="{{ $optimizedStill }}" alt="{{ $episode->name }}" loading="lazy">
                                <div class="episode-play-overlay"><i data-lucide="play"></i></div>
                            </div>
                            <div class="episode-info">
                                <div class="episode-title-row">
                                    <h4>{{ $episode->episode_number }}. {{ $episode->name }}</h4>
                                    <span class="episode-meta">{{ $episode->duration ? $episode->duration . ' min' : '' }}</span>
                                </div>
                                <p class="episode-synopsis">{{ $episode->overview }}</p>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); padding: 40px;">
                            Nenhum episódio encontrado para esta temporada.
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- SEÇÃO DE ELENCO --}}
            @if ($serie->cast->isNotEmpty())
                <section class="details-section">
                    <div class="section-header">
                        <h2><i data-lucide="users"></i> Elenco Principal</h2>
                    </div>

                    <div class="slider-container">
                        <button class="slider-btn prev-btn"><i data-lucide="chevron-left"></i></button>
                        <div class="scroll-wrapper">
                            <div class="cards-row">
                                @foreach ($serie->cast->sortBy('pivot.order') as $actor)
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
                                                $optimizedCast = "https://images.weserv.nl/?url=" . urlencode($castUrl) . "&w=185&h=230&fit=cover&output=webp&q=70";
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
            @if ($similarSeries->isNotEmpty())
                <section class="details-section">
                    <div class="section-header">
                        <h2><i data-lucide="layers"></i> Títulos Semelhantes</h2>
                    </div>

                    <div class="slider-container">
                        <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                        <div class="scroll-wrapper">
                            <div class="cards-row">
                                @foreach ($similarSeries as $similar)
                                    @php
                                        $sPoster = $similar->poster_path;
                                        if ($sPoster) {
                                            if (strpos($sPoster, 'image.tmdb.org') !== false) {
                                                $sPoster = str_replace(['original', 'w500', 'w300'], 'w342', $sPoster);
                                            } elseif (strpos($sPoster, '/') === 0) {
                                                $sPoster = 'https://image.tmdb.org/t/p/w342' . $sPoster;
                                            }
                                        }
                                        $itemUrl = $similar->slug ? route('series.show', $similar->slug) : 'javascript:void(0)';
                                    @endphp
                                    <a href="{{ $itemUrl }}" class="card">
                                        <div class="card-img-wrapper">
                                            @if($sPoster)
                                                @php
                                                    $sCardSrc = "https://images.weserv.nl/?url=" . urlencode($sPoster) . "&w=250&output=webp&q=70";
                                                    $sCardSet = "https://images.weserv.nl/?url=" . urlencode($sPoster) . "&w=180&output=webp&q=70 180w, " . $sCardSrc . " 250w, https://images.weserv.nl/?url=" . urlencode($sPoster) . "&w=342&output=webp&q=70 342w, https://images.weserv.nl/?url=" . urlencode($sPoster) . "&w=500&output=webp&q=70 500w";
                                                @endphp
                                                <img src="{{ $sCardSrc }}" 
                                                     srcset="{{ $sCardSet }}" 
                                                     sizes="(max-width: 640px) 140px, 200px" 
                                                     alt="{{ $similar->name }}" class="card-img" loading="lazy" decoding="async" width="300" height="450">
                                            @else
                                                <div class="card-img placeholder">
                                                    <i data-lucide="tv" class="placeholder-icon"></i>
                                                </div>
                                            @endif
                                            <div class="card-badge">{{ Str::upper($similar->type) }}</div>
                                            <div class="card-overlay">
                                                <div class="play-circle"><i data-lucide="play"></i></div>
                                            </div>
                                        </div>
                                        <div class="card-info">
                                            <h4>{{ $similar->name }}</h4>
                                            <div class="card-meta">
                                                <span>{{ $similar->first_air_year }}</span>
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

    {{-- MODAL DE OPÇÕES DE PLAYER (Dinâmico para Series) --}}
    <div class="modal-overlay" id="optionsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Escolha um Servidor</h3>
                <button class="close-modal-btn" id="closeModalBtn"><i data-lucide="x"></i></button>
            </div>
            <div class="modal-options-list" id="modalOptionsList">
                {{-- Preenchido via JS ao clicar no episódio --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seletor de Temporadas
            const seasonSelect = document.getElementById('seasonSelect');
            if (seasonSelect) {
                seasonSelect.addEventListener('change', function() {
                    const season = this.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('season', season);
                    window.location.href = url.toString();
                });
            }

            // Atualizar Modal com links do episódio
            const episodeItems = document.querySelectorAll('.trigger-modal-play');
            const modalOptionsList = document.getElementById('modalOptionsList');

            episodeItems.forEach(item => {
                item.addEventListener('click', function() {
                    const links = JSON.parse(this.dataset.links || '[]');
                    
                    if (links.length > 0) {
                        modalOptionsList.innerHTML = links.map(link => {
                            let icon = 'play-circle';
                            if (link.name.toLowerCase().includes('legendado')) icon = 'captions';
                            if (link.type === 'embed') icon = 'server';

                            return `
                                <button class="option-item select-server" data-src="${link.url}" data-type="${link.type}">
                                    <i data-lucide="${icon}"></i>
                                    <div>
                                        <div style="font-weight: 700;">${link.name}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                                            ${link.quality || 'HD'} • ${link.type.toUpperCase()}
                                        </div>
                                    </div>
                                </button>
                            `;
                        }).join('');
                        lucide.createIcons();
                    } else {
                        modalOptionsList.innerHTML = '<div style="text-align: center; color: var(--text-muted); padding: 20px;">Nenhum servidor disponível para este episódio.</div>';
                    }
                });
            });
        });
    </script>
@endsection
