<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <a href="{{ url('/') }}" class="logo">
                <h3>FYNECINE</h3>
            </a>
            <p>FYNECINE não hospeda arquivos em seus servidores. O conteúdo é indexado de forma automática.</p>
            <div class="social-links">
                <a href="https://t.me/fynecinex"><i class="fab fa-telegram"></i></a>
                <a href="https://whatsapp.com/channel/0029Va6wcHqAInPjJVZ2jD1H"><i class="fab fa-whatsapp"></i></a>
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