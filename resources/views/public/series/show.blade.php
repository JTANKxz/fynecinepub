@extends('layouts.app')

@section('title', $serie->name)

@php
    $backdrop = $serie->backdrop_path;
    if ($backdrop && strpos($backdrop, '/') === 0) {
        $backdrop = 'https://image.tmdb.org/t/p/original' . $backdrop;
    }
    $backdropFallback = $serie->poster_path;
    if ($backdropFallback && strpos($backdropFallback, '/') === 0) {
        $backdropFallback = 'https://image.tmdb.org/t/p/original' . $backdropFallback;
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

    <div class="backdrop-container" id="backdropContainer"
        style="background-image: url('{{ $backdrop ?? $backdropFallback }}');">
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
        <i class="fas fa-play"></i>
    </button>
    @endif

    {{-- Container do Player --}}
    <div class="player-wrapper" id="playerWrapper">
        <button class="close-player-btn" id="closePlayerBtn">
            <i class="fas fa-arrow-left"></i> Voltar
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
                        if ($poster && strpos($poster, '/') === 0) {
                            $poster = 'https://image.tmdb.org/t/p/w500' . $poster;
                        }
                    @endphp
                    <img src="{{ $poster ?? 'https://placehold.co/400x600/18181b/8b5cf6?text=Sem+Poster' }}"
                        alt="Assistir Série {{ $serie->name }} Online Grátis HD" fetchpriority="high">
                </div>

                <div class="info-wrapper">
                    <h1 class="details-title">{{ $serie->name }}</h1>

                    <div class="details-meta">
                        <span><i class="fas fa-calendar"></i> {{ $serie->first_air_year }} {{ $serie->last_air_year ? '- ' . $serie->last_air_year : '- Presente' }}</span>
                        <span><i class="fas fa-layer-group"></i> {{ $serie->seasons->count() }} Temporadas</span>
                        <span class="rating"><i class="fas fa-star"></i> {{ number_format($serie->rating, 1) }}/10</span>
                        @if ($serie->age_rating)
                            <span class="age-badge"><i class="fas fa-eye"></i> +{{ $serie->age_rating }}</span>
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
                            <i class="fas fa-play"></i> Assistir S{{ $selectedSeasonNumber }}:E1
                        </button>
                        @endif
                        
                        @if ($serie->trailer_url)
                            <a href="{{ $serie->trailer_url }}" target="_blank" class="btn-secondary">
                                <i class="fas fa-film"></i> Ver Trailer
                            </a>
                        @elseif($serie->trailer_key)
                            <a href="https://www.youtube.com/watch?v={{ $serie->trailer_key }}" target="_blank"
                                class="btn-secondary">
                                <i class="fas fa-film"></i> Ver Trailer
                            </a>
                        @endif
                        <button class="btn-secondary" style="padding: 12px 18px;" aria-label="Adicionar aos favoritos">
                            <i class="fas fa-bookmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO DE EPISÓDIOS --}}
            <section class="episodes-section">
                <div class="episodes-header">
                    <h2><i class="fas fa-list-ol"></i> Episódios</h2>
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
                            if ($still && strpos($still, '/') === 0) {
                                $still = 'https://image.tmdb.org/t/p/w300' . $still;
                            }
                        @endphp
                        <div class="episode-item trigger-modal-play" 
                             data-episode-id="{{ $episode->id }}"
                             data-links='@json($episode->linksData)'>
                            <div class="episode-thumb">
                                <img src="{{ $still ?? 'https://placehold.co/320x180/18181b/8b5cf6?text=Episódio+'.$episode->episode_number }}" alt="{{ $episode->name }}">
                                <div class="episode-play-overlay"><i class="fas fa-play"></i></div>
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
                        <h2><i class="fas fa-users"></i> Elenco Principal</h2>
                    </div>

                    <div class="slider-container">
                        <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                        <div class="scroll-wrapper">
                            <div class="cards-row">
                                @foreach ($serie->cast->sortBy('pivot.order') as $actor)
                                    @php
                                        $profile = $actor->profile_path;
                                        if ($profile && strpos($profile, '/') === 0) {
                                            $profile = 'https://image.tmdb.org/t/p/w185' . $profile;
                                        }
                                        $avatarFallback =
                                            'https://www.themoviedb.org/assets/2/v4/glyphicons/basic/glyphicons-basic-4-user-grey-d8fe57731f22442a9c18fb27a971256745c06bbc50776791fe2177a6c0f04adc.svg';
                                    @endphp
                                    <div class="cast-card">
                                        <div class="cast-avatar">
                                            <img src="{{ $profile ?? $avatarFallback }}" alt="{{ $actor->name }}">
                                        </div>
                                        <h5 class="cast-name">{{ $actor->name }}</h5>
                                        <span class="cast-role">{{ $actor->pivot->character }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </section>
            @endif

            {{-- SEÇÃO DE RECOMENDADOS --}}
            @if ($similarSeries->isNotEmpty())
                <section class="details-section">
                    <div class="section-header">
                        <h2><i class="fas fa-layer-group"></i> Títulos Semelhantes</h2>
                    </div>

                    <div class="slider-container">
                        <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                        <div class="scroll-wrapper">
                            <div class="cards-row">
                                @foreach ($similarSeries as $similar)
                                    @php
                                        $sPoster = $similar->poster_path;
                                        if ($sPoster && strpos($sPoster, '/') === 0) {
                                            $sPoster = 'https://image.tmdb.org/t/p/w342' . $sPoster;
                                        }
                                        $itemUrl = $similar->slug ? route('series.show', $similar->slug) : 'javascript:void(0)';
                                    @endphp
                                    <a href="{{ $itemUrl }}" class="card">
                                        <div class="card-img-wrapper">
                                            <div class="card-img" style="background-image: url('{{ $sPoster }}')"></div>
                                            <div class="card-badge">{{ Str::upper($similar->type) }}</div>
                                            <div class="card-overlay">
                                                <div class="play-circle"><i class="fas fa-play"></i></div>
                                            </div>
                                        </div>
                                        <div class="card-info">
                                            <h4>{{ $similar->name }}</h4>
                                            <div class="card-meta">
                                                <span>{{ $similar->first_air_year }}</span>
                                                <span class="rating"><i
                                                        class="fas fa-star"></i>{{ number_format($similar->rating, 1) }}</span>
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
                <button class="close-modal-btn" id="closeModalBtn"><i class="fas fa-times"></i></button>
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
                            let icon = 'fas fa-play-circle';
                            if (link.name.toLowerCase().includes('legendado')) icon = 'fas fa-closed-captioning';
                            if (link.type === 'embed') icon = 'fas fa-server';

                            return `
                                <button class="option-item select-server" data-src="${link.url}" data-type="${link.type}">
                                    <i class="${icon}"></i>
                                    <div>
                                        <div style="font-weight: 700;">${link.name}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                                            ${link.quality || 'HD'} • ${link.type.toUpperCase()}
                                        </div>
                                    </div>
                                </button>
                            `;
                        }).join('');
                    } else {
                        modalOptionsList.innerHTML = '<div style="text-align: center; color: var(--text-muted); padding: 20px;">Nenhum servidor disponível para este episódio.</div>';
                    }
                });
            });
        });
    </script>
@endsection
