<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <a href="{{ url('/') }}" class="logo">
                <h3>FYNECINE</h3>
            </a>
            <p>FYNECINE não hospeda arquivos em seus servidores. O conteúdo é indexado de forma automática.</p>
            <div class="social-links">
                <a href="https://t.me/fynecinex" aria-label="Telegram">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                </a>
                <a href="https://whatsapp.com/channel/0029Va6wcHqAInPjJVZ2jD1H" aria-label="WhatsApp">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-11.8 8.38 8.38 0 0 1 3.8.9L21 3z"/></svg>
                </a>
            </div>
        </div>

        <div>
            <h4 class="footer-title">Navegação</h4>
            <div class="footer-links">
                <a href="{{ url('/') }}">Início</a>
                <a href="{{ url('/filmes') }}">Filmes</a>
                <a href="{{ url('/series') }}">Séries</a>
                <a href="{{ route('landing') }}">Aplicativo</a>
            </div>
        </div>

        <div>
            <h4 class="footer-title">Categorias</h4>
            <div class="footer-links">
                <a href="{{ route('genre.show', 'acao') }}">Ação e Aventura</a>
                <a href="{{ route('genre.show', 'ficcao-cientifica') }}">Ficção Científica</a>
                <a href="{{ route('genre.show', 'terror') }}">Terror e Suspense</a>
                <a href="{{ route('genre.show', 'comedia') }}">Comédia</a>
            </div>
        </div>

        <div>
            <h4 class="footer-title">Legal</h4>
            <div class="footer-links">
                <a href="#">Sobre Nós</a>
                <a href="#">Termos de Uso</a>
                <a href="#">Política de Privacidade</a>
                <a href="#">Contato e Suporte</a>
            </div>
        </div>
    </div>
    <div class="copyright">
        <p>&copy; 2026 FYNECINE. Todos os direitos reservados.</p>
    </div>
</footer>