<header class="header">
    <div class="container header-content">
        <a href="{{ url('/') }}" class="logo">
            <h1>FYNECINE</h1>
            <span>Seu cinema em casa</span>
        </a>

        <div class="header-actions">
            <button class="search-icon-btn" id="searchToggleBtn" aria-label="Buscar">
                <i class="fas fa-search"></i>
            </button>
            <!-- Menu Hamburguer sempre visível -->
            <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <!-- Search Bar Dropdown -->
    <div class="search-dropdown" id="searchDropdown">
        <form action="{{ url('/pesquisa') }}" method="GET" class="search-form">
            <input type="text" placeholder="Buscar filmes ou séries..." id="searchInput" name="q"
                value="{{ request('q') }}">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- Menu Mobile Overlay (Desce a partir do Header) -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-nav">
            <a href="{{ url('/') }}"><i class="fas fa-home"></i> Início</a>
            <a href="{{ url('/filmes') }}"><i class="fas fa-film"></i> Filmes</a>
            <a href="{{ url('/series') }}"><i class="fas fa-tv"></i> Séries</a>
            <a href="{{ route('landing') }}"><i class="fas fa-mobile-alt"></i> Aplicativo</a>
        </div>
    </div>
</header>