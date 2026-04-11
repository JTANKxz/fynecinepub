<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

  {{-- SEO BÁSICO --}}
  <title>@yield('title', 'FYNECINE - Seu cinema em casa')</title>
  <meta name="description"
    content="@yield('description', 'Assista aos melhores filmes e séries online em alta definição no FYNECINE. O seu portal premium de entretenimento.')">
  <meta name="keywords"
    content="@yield('keywords', 'filmes online, séries online, assistir filmes, fynecine, streaming')">
  <link rel="canonical" href="{{ url()->current() }}">
  <meta name="robots" content="index, follow">

  {{-- OPEN GRAPH --}}
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="@yield('title', 'FYNECINE - Seu cinema em casa')">
  <meta property="og:description"
    content="@yield('description', 'Assista aos melhores filmes e séries online em alta definição.')">
  <meta property="og:image" content="@yield('og_image', asset('img/og-fallback.jpg'))">
  <meta property="og:site_name" content="FYNECINE">
  <meta property="og:locale" content="pt_BR">

  {{-- TWITTER --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="{{ url()->current() }}">
  <meta name="twitter:title" content="@yield('title', 'FYNECINE - Seu cinema em casa')">
  <meta name="twitter:description"
    content="@yield('description', 'Assista aos melhores filmes e séries online em alta definição.')">
  <meta name="twitter:image" content="@yield('og_image', asset('img/og-fallback.jpg'))">

  {{-- PERFORMANCE --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://image.tmdb.org">

  <link rel="dns-prefetch" href="https://image.tmdb.org">
  <link rel="dns-prefetch" href="https://cdn.plyr.io">

  {{-- FONTES OTIMIZADAS (menos pesos + swap) --}}
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap"
    media="print" onload="this.media='all'">

  {{-- PRELOADS CRÍTICOS --}}
  <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
  <link rel="preload" href="{{ asset('js/app.js') }}" as="script">
  <link rel="preload" href="{{ asset('js/lucide.min.js') }}" as="script">



  {{-- CSS CRÍTICO INLINE (acima da dobra) --}}
  <style>
    :root { --primary: #8b5cf6; --bg-color: #000000; }
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: var(--bg-color);
      color: #fff;
    }

    .hero-section { padding-top: 110px; }
    .featured-slider {
      width: 100%;
      aspect-ratio: 16 / 9;
      max-height: 600px;
      background: #0a0a0a;
      position: relative;
      overflow: hidden;
      border-radius: 20px;
    }

    .slide {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
    }
    .slide.active { opacity: 1; }

    .slide-bg {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  </style>

  {{-- CSS PRINCIPAL (não bloqueante) --}}
  <link rel="preload" href="{{ asset('css/app.css') }}" as="style" onload="this.rel='stylesheet'">

  <noscript>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  </noscript>

  {{-- PLYR (não crítico) --}}
  <link rel="preload" href="https://cdn.plyr.io/3.7.8/plyr.css" as="style" onload="this.rel='stylesheet'">

  {{-- ESTRUTURA DE DADOS --}}
  <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Organization",
      "name": "FYNECINE",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('img/logo.png') }}",
      "sameAs": [
        "https://t.me/fynecinex",
        "https://whatsapp.com/channel/0029Va6wcHqAInPjJVZ2jD1H"
      ]
    }
  </script>

  <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "WebSite",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@@type": "SearchAction",
        "target": "{{ url('/pesquisa') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
  </script>

  @stack('seo')

</head>

<body>

  {{-- HEADER GLOBAL --}}
  @include('partials.header')

  {{-- CONTEÚDO --}}
  @yield('content')

  {{-- FOOTER GLOBAL --}}
  @include('partials.footer')

  {{-- JS --}}
  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="{{ asset('js/lucide.min.js') }}" defer></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    });
  </script>

  @yield('scripts')

</body>

</html>