// Header com Blur Dinâmico no Scroll
window.addEventListener('scroll', () => {
    const header = document.querySelector('.header');
    if (window.scrollY > 50) {
        header.style.background = 'rgba(0, 0, 0, 0.9)';
        header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.8)';
    } else {
        header.style.background = 'rgba(0, 0, 0, 0.85)';
        header.style.boxShadow = 'none';
    }
});

// Slider Principal Hero
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');

function goToSlide(index) {
    if (!slides.length) return;
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
        if (dots[i]) dots[i].classList.toggle('active', i === index);
    });
    currentSlide = index;
}

function nextSlide() { if (slides.length) goToSlide((currentSlide + 1) % slides.length); }
function prevSlide() { if (slides.length) goToSlide((currentSlide - 1 + slides.length) % slides.length); }

// Auto Advance Slider (8 segundos para dar tempo de ler as descrições)
if (slides.length > 1) {
    setInterval(nextSlide, 8000);
}

// Scroll lateral das seções de Cards
document.querySelectorAll('.section').forEach(section => {
    const prev = section.querySelector('.prev-btn');
    const next = section.querySelector('.next-btn');
    const wrapper = section.querySelector('.scroll-wrapper');
    if (!prev || !next || !wrapper) return;

    const scrollAmount = () => wrapper.clientWidth * 0.75; // Rola 75% da largura visível

    prev.addEventListener('click', () => wrapper.scrollBy({ left: -scrollAmount(), behavior: 'smooth' }));
    next.addEventListener('click', () => wrapper.scrollBy({ left: scrollAmount(), behavior: 'smooth' }));

    const checkButtons = () => {
        const maxScroll = wrapper.scrollWidth - wrapper.clientWidth;
        // Esconde botão de voltar se estiver no começo
        if (wrapper.scrollLeft <= 10) {
            prev.style.opacity = '0';
            prev.style.visibility = 'hidden';
        } else {
            prev.style.visibility = 'visible';
            prev.style.opacity = '1';
        }

        // Esconde botão de avançar se estiver no final
        if (wrapper.scrollLeft >= maxScroll - 10) {
            next.style.opacity = '0';
            next.style.visibility = 'hidden';
        } else {
            next.style.visibility = 'visible';
            next.style.opacity = '1';
        }
    };

    wrapper.addEventListener('scroll', checkButtons);
    window.addEventListener('resize', checkButtons);

    // Inicial setup atrasado pra garantir renderização
    setTimeout(checkButtons, 200);
});

const hamburger = document.getElementById('hamburgerBtn');
const mobileMenu = document.getElementById('mobileMenu');
const searchToggle = document.getElementById('searchToggleBtn');
const searchDropdown = document.getElementById('searchDropdown');
const searchInput = document.getElementById('searchInput');

// Menu Hamburguer
hamburger?.addEventListener('click', () => {
    mobileMenu.classList.toggle('open');
    const spans = hamburger.querySelectorAll('span');

    if (mobileMenu.classList.contains('open')) {
        spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
        spans[1].style.opacity = '0';
        spans[2].style.transform = 'rotate(-45deg) translate(5px, -6px)';

        // Fecha a pesquisa se estiver aberta
        if (searchDropdown?.classList.contains('open')) {
            searchDropdown.classList.remove('open');
        }
    } else {
        spans[0].style.transform = 'none';
        spans[1].style.opacity = '1';
        spans[2].style.transform = 'none';
    }
});

// Barra de pesquisa expansível como dropdown
searchToggle?.addEventListener('click', () => {
    searchDropdown?.classList.toggle('open');

    if (searchDropdown?.classList.contains('open')) {
        searchInput?.focus();

        // Fecha o menu lateral se estiver aberto
        if (mobileMenu?.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            const spans = hamburger.querySelectorAll('span');
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        }
    }
});

// Fecha menus ao clicar fora
document.addEventListener('click', (e) => {
    if (!e.target.closest('.header') && !e.target.closest('#mobileMenu') && !e.target.closest('#searchDropdown')) {
        if (mobileMenu?.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            const spans = hamburger.querySelectorAll('span');
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        }
        if (searchDropdown?.classList.contains('open')) {
            searchDropdown.classList.remove('open');
        }
    }
});

// ==========================================
// LÓGICA DO MODAL DE OPÇÕES E PLAYER CINEMA
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    const optionsModal = document.getElementById('optionsModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    
    const videoPlayerElement = document.getElementById('videoPlayer');
    const iframePlayerElement = document.getElementById('iframePlayer');
    const closePlayerBtn = document.getElementById('closePlayerBtn');

    let player = null;
    let hls = null;

    // Inicialização do Plyr (Se disponível)
    if (typeof Plyr !== 'undefined' && videoPlayerElement) {
        player = new Plyr(videoPlayerElement, {
            captions: { active: true, update: true, language: 'pt' }
        });
    }

    const closeModal = () => {
        if (optionsModal) optionsModal.classList.remove('active');
    };

    // DELEGAÇÃO DE CLIQUES PARA ABRIR MODAL E SELECIONAR SERVIDOR
    document.addEventListener('click', (e) => {
        // 1. Abrir Modal de Servidores
        const trigger = e.target.closest('.trigger-modal-play');
        if (trigger) {
            e.preventDefault();
            if (optionsModal) optionsModal.classList.add('active');
        }

        // 2. Selecionar Servidor e Iniciar Player
        const option = e.target.closest('.select-server');
        if (option) {
            e.preventDefault();
            const videoSrc = option.getAttribute('data-src');
            const type = option.getAttribute('data-type'); // embed, hls, mp4

            // Limpa players atuais
            // Limpa players atuais e esconde containers
            if (hls) {
                hls.destroy();
                hls = null;
            }
            if (player) player.stop();

            const plyrContainer = document.querySelector('.plyr');
            if (plyrContainer) plyrContainer.style.display = 'none';
            if (videoPlayerElement) videoPlayerElement.style.display = 'none';
            if (iframePlayerElement) {
                iframePlayerElement.style.display = 'none';
                iframePlayerElement.src = '';
            }

            if (type === 'embed') {
                if (iframePlayerElement) {
                    iframePlayerElement.style.display = 'block';
                    iframePlayerElement.src = videoSrc;
                }
            } else {
                if (videoPlayerElement) {
                    if (plyrContainer) plyrContainer.style.display = 'block';
                    else videoPlayerElement.style.display = 'block';

                    if (type === 'hls' && typeof Hls !== 'undefined' && Hls.isSupported()) {
                        hls = new Hls();
                        hls.loadSource(videoSrc);
                        hls.attachMedia(videoPlayerElement);
                    } else {
                        videoPlayerElement.src = videoSrc;
                    }

                    setTimeout(() => {
                        if (player) player.play();
                        else videoPlayerElement.play();
                    }, 100);
                }
            }

            // Entrar no Modo Cinema
            closeModal();
            document.body.classList.add('playing-mode');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    // Controles do Modal
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (optionsModal) {
        optionsModal.addEventListener('click', (e) => {
            if (e.target === optionsModal) closeModal();
        });
    }

    // Fechar Player / Sair do Modo Cinema
    if (closePlayerBtn) {
        closePlayerBtn.addEventListener('click', () => {
            document.body.classList.remove('playing-mode');

            if (player) player.stop();
            if (videoPlayerElement) {
                videoPlayerElement.pause();
                videoPlayerElement.src = '';
                videoPlayerElement.style.display = 'none';
            }
            if (iframePlayerElement) {
                iframePlayerElement.src = '';
                iframePlayerElement.style.display = 'none';
            }
            if (hls) {
                hls.destroy();
                hls = null;
            }
        });
    }
});