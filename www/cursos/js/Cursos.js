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

const nomesMaterias = document.querySelectorAll('.materias > h2');
const containersMaterias = document.querySelectorAll('.materias > aside');

nomesMaterias.forEach((nomeMateria, index) => {
    nomeMateria.addEventListener('click', () => {
        const containerMaterias = containersMaterias[index];
        if(!containerMaterias.classList.contains('cardvisivel')) {
            containerMaterias.classList.add('cardvisivel');
        } else {
            containerMaterias.classList.remove('cardvisivel');
        }
    });
});