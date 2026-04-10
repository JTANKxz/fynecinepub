@extends('layouts.app')

@section('title', 'Assistir ' . $q . ' Online Grátis HD | FYNECINE')
@section('description', 'Buscando por ' . $q . '? Assista online grátis no FYNECINE com a melhor qualidade. Resultados encontrados para ' . $q . '.')

@push('seo')
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
    "name": "Pesquisa: {{ $q }}",
    "item": "{{ url()->current() }}"
  }]
}
</script>
@endpush

@section('content')
    <main class="catalog-page">
        <div class="container">
            <!-- Cabeçalho da Página -->
            <div class="page-header">
                <h2 class="page-title">Resultados para: <span>"{{ $q }}"</span></h2>
                <div style="color: var(--text-muted); font-size: 0.9rem;">
                    Encontramos {{ $results->total() }} resultados
                </div>
            </div>

            <!-- GRID PRINCIPAL -->
            <div class="catalog-grid">
                @forelse($results as $item)
                    @php
                        $itemTitle = $item->name;
                        $itemYear = $item->year;
                        $itemRating = $item->rating;
                        $itemPoster = $item->poster_path;
                        if ($itemPoster && strpos($itemPoster, '/') === 0)
                            $itemPoster = 'https://image.tmdb.org/t/p/w500' . $itemPoster;
                        
                        $isSeries = ($item->type === 'series');
                        $itemType = $isSeries ? 'SÉRIE' : 'FILME';
                        $itemIcon = $isSeries ? 'fas fa-tv' : 'fas fa-film';
                        
                        $itemUrl = $item->slug ? route($isSeries ? 'series.show' : 'movies.show', $item->slug) : 'javascript:void(0)';
                    @endphp
                    <a href="{{ $itemUrl }}" class="card">
                        <div class="card-img-wrapper">
                            <div class="card-img" style="background-image: url('{{ $itemPoster ?? 'https://placehold.co/400x600/18181b/8b5cf6?text=Sem+Poster' }}')">
                                @if(!$itemPoster)
                                    <i class="{{ $itemIcon }} placeholder-icon"></i>
                                @endif
                            </div>
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
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px 0; color: var(--text-muted);">
                        <i class="fas fa-search-minus" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.5;"></i>
                        <p>Nenhum resultado encontrado para "{{ $q }}".</p>
                        <a href="{{ url('/') }}" class="btn-assistir" style="margin-top: 20px; display: inline-block;">Voltar para o Início</a>
                    </div>
                @endforelse
            </div>

            <!-- PAGINAÇÃO -->
            @if($results->hasPages())
                <div class="pagination">
                    @if($results->onFirstPage())
                        <div class="page-btn disabled"><i class="fas fa-chevron-left"></i></div>
                    @else
                        <a href="{{ $results->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @php
                        $start = max(1, $results->currentPage() - 2);
                        $end = min($results->lastPage(), $results->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $results->url(1) }}" class="page-btn">1</a>
                        @if($start > 2) <span style="color: var(--text-muted);">...</span> @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        <a href="{{ $results->url($i) }}" class="page-btn {{ $i == $results->currentPage() ? 'active' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if($end < $results->lastPage())
                        @if($end < $results->lastPage() - 1) <span style="color: var(--text-muted);">...</span> @endif
                        <a href="{{ $results->url($results->lastPage()) }}" class="page-btn">{{ $results->lastPage() }}</a>
                    @endif

                    @if($results->hasMorePages())
                        <a href="{{ $results->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <div class="page-btn disabled"><i class="fas fa-chevron-right"></i></div>
                    @endif
                </div>
            @endif
        </div>
    </main>
@endsection
