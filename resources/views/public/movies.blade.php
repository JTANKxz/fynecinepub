@extends('layouts.app')

@section('title', 'Filmes | FYNECINE')

@section('content')
    <main class="catalog-page">
        <div class="container">
            <!-- Cabeçalho da Página com Filtros -->
            <div class="page-header">
                <h2 class="page-title">Filmes <span>({{ $movies->total() }} títulos)</span></h2>

                <form action="{{ url()->current() }}" method="GET" class="filters" id="filterForm">
                    {{-- Mantém a busca se houver --}}
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <select name="genre" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Gênero: Todos</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="year" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Ano: Todos</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    <select name="sort" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="recentes" {{ request('sort') == 'recentes' ? 'selected' : '' }}>Mais Recentes</option>
                        <option value="populares" {{ request('sort') == 'populares' ? 'selected' : '' }}>Mais Populares</option>
                        <option value="avaliacao" {{ request('sort') == 'avaliacao' ? 'selected' : '' }}>Melhor Avaliação</option>
                        <option value="antigos" {{ request('sort') == 'antigos' ? 'selected' : '' }}>Anteriores</option>
                    </select>
                </form>
            </div>

            <!-- GRID PRINCIPAL -->
            <div class="catalog-grid">
                @forelse($movies as $item)
                    @php
                        $itemTitle = $item->title ?? $item->name;
                        $itemYear = $item->release_year ?? $item->first_air_year;
                        $itemRating = $item->rating;
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
                    @endphp
                    @php
                        // Somente gera rota se tiver slug
                        $itemUrl = 'javascript:void(0)';
                        if ($item->slug) {
                            $itemUrl = $isSeries ? route('series.show', $item->slug) : route('movies.show', $item->slug);
                        }
                    @endphp
                    <a href="{{ $itemUrl }}" class="card">
                        <div class="card-img-wrapper">
                            @if($itemPoster)
                                <img src="{{ $itemPoster }}" 
                                     srcset="{{ str_replace('w342', 'w185', $itemPoster) }} 185w, {{ $itemPoster }} 342w, {{ str_replace('w342', 'w500', $itemPoster) }} 500w" 
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
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px 0; color: var(--text-muted);">
                        <i data-lucide="search" style="width: 48px; height: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
                        <p>Nenhum filme encontrado com os filtros selecionados.</p>
                        <a href="{{ url()->current() }}" class="btn-assistir" style="margin-top: 20px;">Limpar Filtros</a>
                    </div>
                @endforelse
            </div>

            <!-- PAGINAÇÃO -->
            @if($movies->hasPages())
                <div class="pagination">
                    @if($movies->onFirstPage())
                        <div class="page-btn disabled"><i data-lucide="chevron-left"></i></div>
                    @else
                        <a href="{{ $movies->previousPageUrl() }}" class="page-btn"><i data-lucide="chevron-left"></i></a>
                    @endif

                    {{-- Mostra um range de páginas --}}
                    @php
                        $start = max(1, $movies->currentPage() - 2);
                        $end = min($movies->lastPage(), $movies->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $movies->url(1) }}" class="page-btn">1</a>
                        @if($start > 2) <span style="color: var(--text-muted);">...</span> @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        <a href="{{ $movies->url($i) }}" class="page-btn {{ $i == $movies->currentPage() ? 'active' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if($end < $movies->lastPage())
                        @if($end < $movies->lastPage() - 1) <span style="color: var(--text-muted);">...</span> @endif
                        <a href="{{ $movies->url($movies->lastPage()) }}" class="page-btn">{{ $movies->lastPage() }}</a>
                    @endif

                    @if($movies->hasMorePages())
                        <a href="{{ $movies->nextPageUrl() }}" class="page-btn"><i data-lucide="chevron-right"></i></a>
                    @else
                        <div class="page-btn disabled"><i data-lucide="chevron-right"></i></div>
                    @endif
                </div>
            @endif
        </div>
    </main>
@endsection