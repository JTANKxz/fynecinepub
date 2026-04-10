// =========================
// HEADER SCROLL OTIMIZADO
// =========================
const header = document.querySelector('.header');

window.addEventListener('scroll', () => {
    if (!header) return;

    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
}, { passive: true });


// =========================
// SLIDER PRINCIPAL
// =========================
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

function nextSlide() {
    if (slides.length) {
        goToSlide((currentSlide + 1) % slides.length);
    }
}

function prevSlide() {
    if (slides.length) {
        goToSlide((currentSlide - 1 + slides.length) % slides.length);
    }
}

if (slides.length > 1) {
    setInterval(nextSlide, 8000);
}


// =========================
// SCROLL LATERAL OTIMIZADO
// =========================
document.querySelectorAll('.section').forEach(section => {
    const prev = section.querySelector('.prev-btn');
    const next = section.querySelector('.next-btn');
    const wrapper = section.querySelector('.scroll-wrapper');

    if (!prev || !next || !wrapper) return;

    let scrollValue = wrapper.clientWidth * 0.75;
    let ticking = false;

    const updateScrollValue = () => {
        scrollValue = wrapper.clientWidth * 0.75;
    };

    const checkButtons = () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                const maxScroll = wrapper.scrollWidth - wrapper.clientWidth;

                prev.classList.toggle('hidden', wrapper.scrollLeft <= 10);
                next.classList.toggle('hidden', wrapper.scrollLeft >= maxScroll - 10);

                ticking = false;
            });

            ticking = true;
        }
    };

    prev.addEventListener('click', () => {
        wrapper.scrollBy({ left: -scrollValue, behavior: 'smooth' });
    });

    next.addEventListener('click', () => {
        wrapper.scrollBy({ left: scrollValue, behavior: 'smooth' });
    });

    wrapper.addEventListener('scroll', checkButtons, { passive: true });
    window.addEventListener('resize', () => {
        updateScrollValue();
        checkButtons();
    });

    setTimeout(checkButtons, 200);
});


// =========================
// MENU E BUSCA
// =========================
const hamburger = document.getElementById('hamburgerBtn');
const mobileMenu = document.getElementById('mobileMenu');
const searchToggle = document.getElementById('searchToggleBtn');
const searchDropdown = document.getElementById('searchDropdown');
const searchInput = document.getElementById('searchInput');

const resetHamburger = () => {
    const spans = hamburger?.querySelectorAll('span');
    if (!spans) return;

    spans[0].style.transform = 'none';
    spans[1].style.opacity = '1';
    spans[2].style.transform = 'none';
};

hamburger?.addEventListener('click', () => {
    mobileMenu.classList.toggle('open');

    const spans = hamburger.querySelectorAll('span');

    if (mobileMenu.classList.contains('open')) {
        spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
        spans[1].style.opacity = '0';
        spans[2].style.transform = 'rotate(-45deg) translate(5px, -6px)';

        searchDropdown?.classList.remove('open');
    } else {
        resetHamburger();
    }
});

searchToggle?.addEventListener('click', () => {
    searchDropdown?.classList.toggle('open');

    if (searchDropdown?.classList.contains('open')) {
        searchInput?.focus();

        if (mobileMenu?.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            resetHamburger();
        }
    }
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.header') &&
        !e.target.closest('#mobileMenu') &&
        !e.target.closest('#searchDropdown')) {

        if (mobileMenu?.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            resetHamburger();
        }

        searchDropdown?.classList.remove('open');
    }
});


// =========================
// MODAL + PLAYER
// =========================
document.addEventListener('DOMContentLoaded', () => {
    const optionsModal = document.getElementById('optionsModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

    const videoPlayerElement = document.getElementById('videoPlayer');
    const iframePlayerElement = document.getElementById('iframePlayer');
    const closePlayerBtn = document.getElementById('closePlayerBtn');

    const plyrContainer = document.querySelector('.plyr');

    let player = null;
    let hls = null;

    if (typeof Plyr !== 'undefined' && videoPlayerElement) {
        player = new Plyr(videoPlayerElement, {
            captions: { active: true, update: true, language: 'pt' }
        });
    }

    const closeModal = () => {
        optionsModal?.classList.remove('active');
    };

    document.addEventListener('click', (e) => {

        const trigger = e.target.closest('.trigger-modal-play');
        if (trigger) {
            e.preventDefault();
            optionsModal?.classList.add('active');
        }

        const option = e.target.closest('.select-server');
        if (option) {
            e.preventDefault();

            const videoSrc = option.getAttribute('data-src');
            const type = option.getAttribute('data-type');

            if (hls) {
                hls.destroy();
                hls = null;
            }

            player?.stop();

            if (plyrContainer) plyrContainer.classList.add('hidden');
            videoPlayerElement?.classList.add('hidden');

            if (iframePlayerElement) {
                iframePlayerElement.classList.add('hidden');
                iframePlayerElement.src = '';
            }

            if (type === 'embed') {
                iframePlayerElement?.classList.remove('hidden');
                iframePlayerElement.src = videoSrc;

            } else if (videoPlayerElement) {

                if (plyrContainer) plyrContainer.classList.remove('hidden');
                else videoPlayerElement.classList.remove('hidden');

                if (type === 'hls' && typeof Hls !== 'undefined' && Hls.isSupported()) {
                    hls = new Hls();
                    hls.loadSource(videoSrc);
                    hls.attachMedia(videoPlayerElement);
                } else {
                    videoPlayerElement.src = videoSrc;
                }

                setTimeout(() => {
                    player ? player.play() : videoPlayerElement.play();
                }, 100);
            }

            closeModal();
            document.body.classList.add('playing-mode');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    closeModalBtn?.addEventListener('click', closeModal);

    optionsModal?.addEventListener('click', (e) => {
        if (e.target === optionsModal) closeModal();
    });

    closePlayerBtn?.addEventListener('click', () => {
        document.body.classList.remove('playing-mode');

        player?.stop();

        if (videoPlayerElement) {
            videoPlayerElement.pause();
            videoPlayerElement.src = '';
            videoPlayerElement.classList.add('hidden');
        }

        if (iframePlayerElement) {
            iframePlayerElement.src = '';
            iframePlayerElement.classList.add('hidden');
        }

        if (plyrContainer) plyrContainer.classList.add('hidden');

        if (hls) {
            hls.destroy();
            hls = null;
        }
    });
});