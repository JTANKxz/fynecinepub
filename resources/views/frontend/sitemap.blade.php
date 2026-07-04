{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>{{ route('frontend.search') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('frontend.app-download') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ route('terms') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc>{{ route('privacy') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    @foreach($movies as $movie)
        @php
            $movieSlug = trim((string) ($movie->slug ?? ''));
            $movieUpdatedAt = $movie->updated_at ?? null;
        @endphp
        @if($movieSlug !== '')
        <url>
            <loc>{{ route('frontend.movie', $movieSlug) }}</loc>
            <lastmod>{{ $movieUpdatedAt ? $movieUpdatedAt->toAtomString() : now()->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
        @endif
    @endforeach

    @foreach($series as $serie)
        @php
            $serieSlug = trim((string) ($serie->slug ?? ''));
            $serieUpdatedAt = $serie->updated_at ?? null;
        @endphp
        @if($serieSlug !== '')
        <url>
            <loc>{{ route('frontend.serie', $serieSlug) }}</loc>
            <lastmod>{{ $serieUpdatedAt ? $serieUpdatedAt->toAtomString() : now()->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
        @endif
    @endforeach

</urlset>
