class Menu {
    constructor(menuSelector, menuBtnSelector, menuCloseBtnSelector) {
        this.menu = document.querySelector(menuSelector);
        this.menuBtn = document.querySelector(menuBtnSelector);
        this.menuCloseBtn = document.querySelector(menuCloseBtnSelector); // Define o breakpoint para mobile
        this.checkWindowSize();
        window.addEventListener('resize', () => this.checkWindowSize());
    }

    checkWindowSize() {
        if (window.innerWidth > 1024) {
            this.disableMenu();
        } else {
            this.enableMenu();
        }
    }

    enableMenu() {
        this.menuBtn.addEventListener('click', this.toggleMenu.bind(this));
        this.menuCloseBtn.addEventListener('click', this.toggleMenu.bind(this));
        document.addEventListener('click', this.outsideClick.bind(this));
        this.menuBtn.style.display = 'block';
    }

    disableMenu() {
        this.menuBtn.removeEventListener('click', this.toggleMenu.bind(this));
        this.menuCloseBtn.removeEventListener('click', this.toggleMenu.bind(this));
        document.removeEventListener('click', this.outsideClick.bind(this));
        this.closeMenu(); // Garante que o menu seja fechado se a tela for redimensionada
        this.menuBtn.style.display = 'none';
        this.menu.classList.remove('open');
    }

    outsideClick(event) {
        const isClickInsideMenu = this.menu.contains(event.target);
        const isClickInsideMenuBtn = this.menuBtn.contains(event.target) || this.menuCloseBtn.contains(event.target);
        if (!isClickInsideMenu && !isClickInsideMenuBtn) {
            this.closeMenu();
        }
    }

    toggleMenu() {
        const isOpen = this.menu.classList.contains('open');
        if (isOpen) {
            this.closeMenu();
        } else {
            this.openMenu();
        }
    }

    openMenu() {
        this.menu.classList.add('open');
        this.menuBtn.style.display = 'none';
        this.menuCloseBtn.style.display = 'block';
    }

    closeMenu() {
        this.menu.classList.remove('open');
        this.menuBtn.style.display = 'block';
        this.menuCloseBtn.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const menu = new Menu('#navPrincipal', '#menu', '#menuClose');
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            preloader.remove()
        });
    }
    const backtotop = document.getElementById('backToTop');
    if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY >= 100) {
                backtotop.classList.add('active')
            } else {
                backtotop.classList.remove('active')
            }
        }
        window.addEventListener('scroll', function () {
            toggleBacktotop()
        })
    }
    const header = document.querySelector('#header')
    if (header) {
        const HeaderFixo = () => {
            if (window.scrollY >= 90) {
                header.classList.add('headerFixo')
            } else {
                header.classList.remove('headerFixo')
            }
        }
        window.addEventListener('scroll', function () {
            HeaderFixo()
        })
    }
});

const slider = document.getElementById('slider');
const slide = document.querySelectorAll('.slides');
const setaAnterior = document.getElementById('SetaAnterior');
const setaProxima = document.getElementById('SetaProxima');
let currentIndex = 0;
let interval;

function atualizarSlider() {
    const slideWidth = slide[0].clientWidth;
    slider.style.transform = `translateX(-${currentIndex * (slideWidth)}px)`;
}

function slidePara(index) {
    currentIndex = index;
    atualizarSlider();
}

function nextSlide() {
    currentIndex = (currentIndex + 1) % slide.length;
    atualizarSlider();
}

function prevSlide() {
    currentIndex = (currentIndex - 1 + slide.length) % slide.length;
    atualizarSlider();
}

setaAnterior.addEventListener('click', () => {
    prevSlide();
    clearInterval(interval);
    interval = setInterval(nextSlide, 10000);
});

setaProxima.addEventListener('click', () => {
    nextSlide();
    clearInterval(interval);
    interval = setInterval(nextSlide, 10000);
});

// Adicione uma verificação para rolagem infinita
interval = setInterval(() => {
    nextSlide();
    if (currentIndex === slide.length - 1) {
        setTimeout(() => {
            currentIndex = 0;
            atualizarSlider();
        }, 500); // Atraso para criar um efeito suave de rolagem infinita
    }
}, 10000);

const nomesCursos = document.querySelectorAll('.containerCards > h2');
const containersCards = document.querySelectorAll('.containerCards > article');

if (window.innerWidth < 768) {

    nomesCursos.forEach((nomeCurso, index) => {
        nomeCurso.addEventListener('click', () => {
            const containerCard = containersCards[index];
            if (!containerCard.classList.contains('cardvisivel')) {
                containerCard.classList.add('cardvisivel');
            } else {
                containerCard.classList.remove('cardvisivel');
            }
        });
    });
}


const Servicos = document.querySelector('.servicos > h1');
const AsideServicos = document.querySelector('.servicos > aside');

Servicos.addEventListener('click', () => {
    if (!AsideServicos.classList.contains('active')) {
        AsideServicos.classList.add('active');
    } else {
        AsideServicos.classList.remove('active');
    }
});
