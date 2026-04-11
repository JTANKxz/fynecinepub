@extends('layouts.app')

@section('title', 'Assistir os Melhores Filmes e Séries de ' . $genre->name . ' Grátis | FYNECINE')
@section('description', 'Os melhores lançamentos de ' . $genre->name . ' online pra você assistir agora grátis em HD. Confira o catálogo completo de ' . $genre->name . ' no FYNECINE.')

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
    "name": "Gênero: {{ $genre->name }}",
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
                <h2 class="page-title">Gênero: <span>"{{ $genre->name }}"</span></h2>
                <div style="color: var(--text-muted); font-size: 0.9rem;">
                    Encontramos {{ $results->total() }} títulos neste gênero
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
                        if ($itemPoster) {
                            if (strpos($itemPoster, 'image.tmdb.org') !== false) {
                                $itemPoster = str_replace(['original', 'w500', 'w300'], 'w342', $itemPoster);
                            } elseif (strpos($itemPoster, '/') === 0) {
                                $itemPoster = 'https://image.tmdb.org/t/p/w342' . $itemPoster;
                            }
                        }
                        
                        $isSeries = ($item->type === 'series');
                        $itemType = $isSeries ? 'SÉRIE' : 'FILME';
                        $itemIcon = $isSeries ? 'tv' : 'film';
                        
                        $itemUrl = $item->slug ? route($isSeries ? 'series.show' : 'movies.show', $item->slug) : 'javascript:void(0)';
                    @endphp
                    <a href="{{ $itemUrl }}" class="card">
                        <div class="card-img-wrapper">
                            @if($itemPoster)
                                @php
                                    $cardSrc = "https://images.weserv.nl/?url=" . urlencode($itemPoster) . "&w=342&output=webp&q=80";
                                    $cardSet = "https://images.weserv.nl/?url=" . urlencode($itemPoster) . "&w=185&output=webp&q=80 185w, " . $cardSrc . " 342w, https://images.weserv.nl/?url=" . urlencode($itemPoster) . "&w=500&output=webp&q=80 500w";
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
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px 0; color: var(--text-muted);">
                        <i data-lucide="drama" style="width: 48px; height: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
                        <p>Ainda não temos títulos cadastrados para o gênero "{{ $genre->name }}".</p>
                        <a href="{{ url('/') }}" class="btn-assistir" style="margin-top: 20px; display: inline-block;">Voltar para o Início</a>
                    </div>
                @endforelse
            </div>

            <!-- PAGINAÇÃO -->
            @if($results->hasPages())
                <div class="pagination">
                    @if($results->onFirstPage())
                        <div class="page-btn disabled"><i data-lucide="chevron-left"></i></div>
                    @else
                        <a href="{{ $results->previousPageUrl() }}" class="page-btn"><i data-lucide="chevron-left"></i></a>
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
                        <a href="{{ $results->nextPageUrl() }}" class="page-btn"><i data-lucide="chevron-right"></i></a>
                    @else
                        <div class="page-btn disabled"><i data-lucide="chevron-right"></i></div>
                    @endif
                </div>
            @endif
        </div>
    </main>
@endsection
