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

  {{-- OPEN GRAPH / FACEBOOK / TELEGRAM / WHATSAPP --}}
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

  {{-- PERFORMANCE HINTS --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://image.tmdb.org">
  <link rel="dns-prefetch" href="https://image.tmdb.org">
  <link rel="dns-prefetch" href="https://cdn.plyr.io">

  <!-- Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap"
    rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  {{-- ESTRUTURA DE DADOS (SCHEMA.ORG) --}}
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

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  <!-- CSS do Player Plyr (Premium UI) -->
  <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
</head>

<body>

  {{-- HEADER GLOBAL --}}
  @include('partials.header')

  {{-- CONTEÚDO --}}
  @yield('content')

  {{-- FOOTER GLOBAL --}}
  @include('partials.footer')

  {{-- JS --}}
  <script src="{{ asset('js/app.js') }}"></script>

  @yield('scripts')

</body>

</html>