{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Home Page --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Filmes Catalog --}}
    <url>
        <loc>{{ url('/filmes') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Séries Catalog --}}
    <url>
        <loc>{{ url('/series') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Filmes Individuais --}}
    @foreach($movies as $movie)
    @if($movie->slug)
    <url>
        <loc>{{ route('movies.show', $movie->slug) }}</loc>
        <lastmod>{{ $movie->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endif
    @endforeach

    {{-- Séries Individuais --}}
    @foreach($series as $serie)
    @if($serie->slug)
    <url>
        <loc>{{ route('series.show', $serie->slug) }}</loc>
        <lastmod>{{ $serie->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endif
    @endforeach
</urlset>
