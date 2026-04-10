<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    
    {{-- SEO Estratégico para Landing --}}
    <title>FYNECINE | Assistir Filmes Online Grátis Dublado e Séries HD</title>
    <meta name="description" content="FYNECINE - Seu cinema em casa. Mais de 7000 filmes, 1000 séries, canais e esportes ao vivo totalmente grátis em HD.">
    <meta name="keywords" content="assistir filmes online gratis, assistir series online, fynecine apk, baixar app filmes, futebol ao vivo gratis">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ESTRUTURA DE DADOS (SCHEMA.ORG) --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "FYNECINE",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('img/logo.png') }}",
      "description": "Seu cinema em casa. Filmes, séries e TV ao vivo grátis."
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/pesquisa') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-color: #000000;
            --bg-surface: #0a0a0a;
            --bg-surface-light: #121214;
            --bg-glass: rgba(0, 0, 0, 0.85);
            --primary: #8b5cf6;
            --primary-hover: #a78bfa;
            --primary-glow: rgba(139, 92, 246, 0.5);
            --text-main: #f4f4f5;
            --text-muted: #a1a1aa;
            --border-color: rgba(255, 255, 255, 0.08);
            --radius-md: 12px;
            --radius-lg: 24px;
            --radius-full: 9999px;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --edge-padding: clamp(16px, 5vw, 40px);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: #27272a; border-radius: var(--radius-full); }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        a { text-decoration: none; color: inherit; }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--edge-padding);
        }

        /* ===== BACKGROUND GLOWS ===== */
        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.3;
            z-index: 0;
            pointer-events: none;
            animation: pulse 8s infinite alternate;
        }
        .glow-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: var(--primary); }
        .glow-2 { top: 40%; right: -10%; width: 40vw; height: 40vw; background: #4c1d95; animation-delay: 2s; }
        .glow-3 { bottom: -10%; left: 20%; width: 60vw; height: 60vw; background: #2e1065; animation-delay: 4s; }

        @keyframes pulse {
            0% { transform: scale(1) translate(0, 0); opacity: 0.2; }
            50% { transform: scale(1.1) translate(2%, 2%); opacity: 0.35; }
            100% { transform: scale(0.95) translate(-2%, -2%); opacity: 0.2; }
        }

        /* ===== HEADER PREMIUM ===== */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            background: transparent;
            transition: var(--transition);
        }

        .header.scrolled {
            padding: 16px 0;
            background: var(--bg-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
        }

        .header-content { display: flex; align-items: center; justify-content: space-between; }

        .logo { display: flex; flex-direction: column; justify-content: center; z-index: 1001; }
        .logo h1 {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(135deg, #d8b4fe, var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -1.5px;
            line-height: 1;
        }
        .logo span {
            font-size: 0.7rem; color: var(--primary-hover);
            font-weight: 600; text-transform: uppercase;
            letter-spacing: 2px; margin-top: 2px;
        }

        .desktop-nav { display: none; align-items: center; gap: 32px; font-weight: 600; font-size: 0.95rem; }
        .desktop-nav a { color: var(--text-muted); transition: var(--transition); }
        .desktop-nav a:hover, .desktop-nav a.active { color: var(--text-main); }

        .header-actions { display: flex; align-items: center; gap: 16px; }

        .btn-outline {
            background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color);
            color: var(--text-main); padding: 10px 24px; border-radius: var(--radius-full);
            font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: var(--transition);
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        }
        .header-actions .btn-outline { display: none; }
        .btn-outline:hover { background: rgba(255, 255, 255, 0.1); border-color: var(--primary); }

        .btn-primary {
            background: var(--primary); color: white; padding: 10px 24px; border-radius: var(--radius-full);
            font-weight: 700; font-size: 0.9rem; cursor: pointer; border: none; transition: var(--transition);
            box-shadow: 0 4px 15px var(--primary-glow); display: flex; align-items: center; gap: 8px;
        }
        .btn-primary:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 8px 25px var(--primary-glow); }

        @media (min-width: 1024px) {
            .desktop-nav { display: flex; }
            .header-actions .btn-outline { display: inline-flex; }
            .hamburger { display: none !important; }
        }

        .hamburger {
            display: flex; flex-direction: column; justify-content: center; gap: 5px;
            width: 40px; height: 40px; cursor: pointer; z-index: 1100;
            background: transparent; border: 1px solid var(--border-color);
            border-radius: 50%; align-items: center; transition: var(--transition);
        }
        .hamburger:hover { background: var(--bg-surface); border-color: var(--primary); }
        .hamburger span { display: block; height: 2px; width: 18px; background-color: var(--text-main); border-radius: 2px; transition: var(--transition); }

        .mobile-menu {
            position: absolute; top: 100%; left: 0; width: 100%;
            background: var(--bg-surface); border-bottom: 1px solid var(--border-color);
            padding: 20px var(--edge-padding) 30px; box-shadow: 0 20px 30px rgba(0, 0, 0, 0.8);
            opacity: 0; visibility: hidden; transform: translateY(-20px); transition: var(--transition); z-index: 999;
        }
        .mobile-menu.open { opacity: 1; visibility: visible; transform: translateY(0); }
        .mobile-nav { display: flex; flex-direction: column; gap: 12px; }
        .mobile-nav a {
            font-size: 1.1rem; font-weight: 600; padding: 12px 20px; border-radius: var(--radius-md);
            display: flex; align-items: center; gap: 16px; background: rgba(255, 255, 255, 0.02);
            border: 1px solid transparent; transition: var(--transition);
        }
        .mobile-nav a i { color: var(--primary); font-size: 1.2rem; width: 24px; text-align: center; }

        /* ===== HERO SECTION ===== */
        .hero {
            position: relative; padding-top: 160px; padding-bottom: 80px; min-height: 100vh;
            display: flex; align-items: center; overflow: hidden; z-index: 1;
        }
        .hero-grid { display: grid; grid-template-columns: 1fr; gap: 60px; align-items: center; }
        @media (min-width: 1024px) { .hero-grid { grid-template-columns: 1.1fr 0.9fr; } }
        .hero-content { display: flex; flex-direction: column; gap: 24px; text-align: left; z-index: 2; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px; background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.3); color: var(--primary-hover); padding: 6px 16px;
            border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 700; width: max-content;
        }
        .hero-title { font-size: clamp(2.5rem, 6vw, 4.5rem); font-weight: 900; line-height: 1.1; letter-spacing: -1.5px; text-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); }
        .hero-title span { background: linear-gradient(135deg, #d8b4fe, var(--primary)); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .hero-subtitle { font-size: clamp(1.1rem, 2vw, 1.3rem); color: var(--text-muted); line-height: 1.6; max-width: 600px; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 16px; }
        .hero-actions .btn-primary { padding: 16px 36px; font-size: 1.1rem; }
        .hero-actions .btn-secondary {
            padding: 16px 36px; font-size: 1.1rem; background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color); color: var(--text-main); border-radius: var(--radius-full);
            font-weight: 700; cursor: pointer; transition: var(--transition); display: flex; align-items: center; gap: 12px; backdrop-filter: blur(8px);
        }
        .hero-actions .btn-secondary:hover { background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.2); transform: translateY(-2px); }

        .hero-mockup { position: relative; z-index: 2; perspective: 1000px; }
        .mockup-main {
            width: 100%; aspect-ratio: 16 / 9; background: var(--bg-surface-light);
            border-radius: var(--radius-lg); border: 1px solid var(--border-color);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(255, 255, 255, 0.05);
            overflow: hidden; transform: rotateY(-10deg) rotateX(5deg); transition: transform 0.5s ease; position: relative;
        }
        .hero-mockup:hover .mockup-main { transform: rotateY(0deg) rotateX(0deg); }
        .mockup-main img { width: 100%; height: 100%; object-fit: cover; opacity: 0.8; }
        
        .float-card {
            position: absolute; background: var(--bg-glass); backdrop-filter: blur(12px);
            border: 1px solid var(--border-color); padding: 12px 20px; border-radius: var(--radius-md);
            display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            animation: float 4s ease-in-out infinite;
        }
        .float-1 { bottom: -20px; left: -20px; animation-delay: 0s; }
        .float-2 { top: 40px; right: -30px; animation-delay: 2s; }
        .float-card i { font-size: 1.5rem; color: var(--primary); }
        .float-card h5 { font-size: 0.95rem; font-weight: 700; margin-bottom: 2px; }
        .float-card p { font-size: 0.75rem; color: var(--text-muted); }

        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-15px); } 100% { transform: translateY(0px); } }

        /* ===== STATS SECTION ===== */
        .stats-section { padding: 60px 0; position: relative; z-index: 2; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); background: linear-gradient(90deg, rgba(10, 10, 10, 0.2), rgba(139, 92, 246, 0.05), rgba(10, 10, 10, 0.2)); }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; text-align: center; }
        @media (min-width: 768px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }
        .stat-number { font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; background: linear-gradient(to bottom, #fff, #a1a1aa); -webkit-background-clip: text; background-clip: text; color: transparent; line-height: 1; }
        .stat-label { font-size: clamp(0.9rem, 1.2vw, 1.1rem); font-weight: 600; color: var(--primary-hover); text-transform: uppercase; letter-spacing: 1px; }

        /* ===== FEATURES SECTION ===== */
        .features-section { padding: 100px 0; position: relative; z-index: 2; }
        .feature-row { display: grid; grid-template-columns: 1fr; gap: 50px; align-items: center; margin-bottom: 120px; }
        @media (min-width: 1024px) { .feature-row { grid-template-columns: 1fr 1fr; gap: 80px; } .feature-row.reverse .feature-content { order: 2; } .feature-row.reverse .feature-visual { order: 1; } }
        .feature-content h2 { font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; line-height: 1.2; margin-bottom: 20px; letter-spacing: -1px; }
        .feature-content p { font-size: 1.15rem; color: var(--text-muted); margin-bottom: 30px; line-height: 1.7; }
        .feature-list { list-style: none; display: flex; flex-direction: column; gap: 16px; }
        .feature-list li { display: flex; align-items: flex-start; gap: 16px; font-size: 1.05rem; font-weight: 500; }
        .feature-list i { color: var(--primary); font-size: 1.2rem; margin-top: 3px; }

        .feature-visual { position: relative; }
        .visual-main { width: 100%; border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8); border: 1px solid var(--border-color); background: var(--bg-surface-light); }
        .visual-main img { width: 100%; height: auto; display: block; opacity: 0.8; }
        .visual-floating { position: absolute; bottom: -30px; right: -20px; width: 40%; border-radius: var(--radius-md); overflow: hidden; box-shadow: 0 15px 30px rgba(0,0,0,0.9); border: 1px solid var(--border-color); animation: float 5s ease-in-out infinite reverse; }

        /* ===== CTA SECTION ===== */
        .cta-section { padding: 80px 0; position: relative; z-index: 2; }
        .cta-box { background: linear-gradient(135deg, var(--bg-surface-light) 0%, #1a0b2e 100%); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: var(--radius-lg); padding: clamp(40px, 6vw, 80px) 20px; text-align: center; position: relative; overflow: hidden; }
        .cta-content { position: relative; z-index: 1; max-width: 800px; margin: 0 auto; }
        .cta-box h2 { font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 900; margin-bottom: 20px; }
        .cta-buttons { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }

        /* ===== FOOTER ===== */
        .footer { background: var(--bg-color); border-top: 1px solid var(--border-color); padding: 80px 0 30px; margin-top: 40px; position: relative; z-index: 2; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 60px; }
        .footer-brand .logo h3 { font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #d8b4fe, var(--primary)); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .footer-title { color: white; font-size: 1.05rem; font-weight: 700; margin-bottom: 24px; }
        .footer-links { display: flex; flex-direction: column; gap: 14px; }
        .footer-links a { color: var(--text-muted); font-size: 0.95rem; transition: var(--transition); }
        .footer-links a:hover { color: var(--primary-hover); transform: translateX(4px); }
        .social-links { display: flex; gap: 16px; margin-top: 24px; }
        .social-links a { width: 40px; height: 40px; border-radius: 50%; background: var(--bg-surface-light); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); transition: var(--transition); }
        .social-links a:hover { background: var(--primary); transform: translateY(-3px); }
        .copyright { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.05); color: #52525b; font-size: 0.9rem; }

        @media (max-width: 1023px) { .footer-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 640px) { .footer-grid { grid-template-columns: 1fr; gap: 40px; } }
    </style>
</head>

<body>

    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    <div class="ambient-glow glow-3"></div>

    <header class="header" id="header">
        <div class="container header-content">
            <a href="{{ url('/') }}" class="logo">
                <h1>FYNECINE</h1>
                <span>Seu cinema em casa</span>
            </a>

            <nav class="desktop-nav">
                <a href="#inicio" class="active">Início</a>
                <a href="#recursos">Funcionalidades</a>
                <a href="#dispositivos">Dispositivos</a>
                <a href="#download">Baixar</a>
            </nav>

            <div class="header-actions">
                <a href="{{ url('/') }}" class="btn-outline"><i class="fas fa-laptop"></i> Acessar Web Player</a>
                <a href="#download" class="btn-primary"><i class="fab fa-android"></i> Download App</a>

                <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>

        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-nav">
                <a href="#inicio"><i class="fas fa-home"></i> Início</a>
                <a href="#recursos"><i class="fas fa-star"></i> Funcionalidades</a>
                <a href="#download"><i class="fas fa-download"></i> Download APK</a>
                <a href="{{ url('/') }}" style="color: var(--primary); border-color: var(--primary);"><i class="fas fa-globe"></i> Acessar Web Player</a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero" id="inicio">
            <div class="container hero-grid">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-bolt"></i> Versão 2.5 Disponível
                    </div>
                    <h1 class="hero-title">O Universo do Entretenimento em <span>Um Só Lugar</span></h1>
                    <p class="hero-subtitle">
                        No <strong>FYNECINE</strong> você encontra o maior catálogo de filmes, séries, animes, canais de TV e esportes ao vivo. Totalmente grátis e sem anúncios.
                    </p>
                    <div class="hero-actions">
                        <a href="#download" class="btn-primary"><i class="fas fa-download"></i> Baixar APK Grátis</a>
                        <a href="{{ url('/') }}" class="btn-secondary"><i class="fas fa-play-circle"></i> Assistir na Web</a>
                    </div>
                </div>

                <div class="hero-mockup">
                    <div class="mockup-main">
                        <img src="https://placehold.co/1280x720/121214/8b5cf6?text=FYNECINE+Premium+Interface" alt="Interface do App">
                    </div>
                    <div class="float-card float-1">
                        <i class="fas fa-video"></i>
                        <div><h5>7.000+ Filmes</h5><p>Em Full HD</p></div>
                    </div>
                    <div class="float-card float-2">
                        <i class="fas fa-satellite-dish"></i>
                        <div><h5>TV ao Vivo</h5><p>Canais 24h</p></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats-section">
            <div class="container stats-grid">
                <div class="stat-item"><div class="stat-number">+7.000</div><div class="stat-label">Filmes & Animes</div></div>
                <div class="stat-item"><div class="stat-number">+1.000</div><div class="stat-label">Séries</div></div>
                <div class="stat-item"><div class="stat-number">24/7</div><div class="stat-label">TV Ao Vivo</div></div>
                <div class="stat-item"><div class="stat-number">100%</div><div class="stat-label">Gratuito</div></div>
            </div>
        </section>

        <section class="features-section" id="recursos">
            <div class="container">
                <div class="feature-row">
                    <div class="feature-content">
                        <h2>Catálogo infinito na palma da sua mão.</h2>
                        <p>Diga adeus a dezenas de assinaturas. O FYNECINE unifica lançamentos, séries clássicas e animes com qualidade HD.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Atualizações diárias.</li>
                            <li><i class="fas fa-check-circle"></i> Qualidade de vídeo adaptável.</li>
                            <li><i class="fas fa-check-circle"></i> Continue de onde parou.</li>
                        </ul>
                    </div>
                    <div class="feature-visual">
                        <div class="visual-main" style="aspect-ratio: 4/3;">
                            <img src="https://placehold.co/800x600/121214/8b5cf6?text=Series+Catalog" alt="Catálogo">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta-section" id="download">
            <div class="container">
                <div class="cta-box">
                    <div class="cta-content">
                        <h2>Pronto para começar?</h2>
                        <p>Baixe o aplicativo agora mesmo ou acesse pelo navegador.</p>
                        <div class="cta-buttons">
                            <a href="#" class="btn-primary"><i class="fab fa-android"></i> Baixar para Android</a>
                            <a href="{{ url('/') }}" class="btn-outline"><i class="fas fa-laptop"></i> Acessar Web Player</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <a href="#" class="logo"><h3>FYNECINE</h3></a>
                <p>O FYNECINE não hospeda arquivos em seus servidores. O conteúdo é indexado de forma automática.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-telegram-plane"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div>
                <h4 class="footer-title">Plataforma</h4>
                <div class="footer-links">
                    <a href="{{ url('/') }}">Web Player</a>
                    <a href="#download">Android App</a>
                </div>
            </div>
            <div>
                <h4 class="footer-title">Suporte</h4>
                <div class="footer-links">
                    <a href="#">Ajuda</a>
                    <a href="#">Termos</a>
                    <a href="#">Privacidade</a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2026 FYNECINE. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const header = document.getElementById('header');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) header.classList.add('scrolled');
                else header.classList.remove('scrolled');
            });

            const hamburger = document.getElementById('hamburgerBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            hamburger?.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
                const spans = hamburger.querySelectorAll('span');
                if (mobileMenu.classList.contains('open')) {
                    spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                    spans[1].style.opacity = '0';
                    spans[2].style.transform = 'rotate(-45deg) translate(5px, -6px)';
                } else {
                    spans[0].style.transform = 'none'; spans[1].style.opacity = '1'; spans[2].style.transform = 'none';
                }
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        window.scrollTo({ top: target.offsetTop - 80, behavior: "smooth" });
                    }
                });
            });
        });
    </script>
</body>
</html>
