class Menu {
    constructor(menuSelector, menuBtnSelector, menuCloseBtnSelector) {
        this.menu = document.querySelector(menuSelector);
        this.menuBtn = document.querySelector(menuBtnSelector);
        this.menuCloseBtn = document.querySelector(menuCloseBtnSelector);
        this.breakpoint = 1024; // Define o breakpoint para mobile
        this.checkWindowSize();
        window.addEventListener('resize', () => this.checkWindowSize());
    }

    checkWindowSize() {
        if (window.innerWidth > this.breakpoint) {
            this.disableMenu();
        } else {
            this.enableMenu();
        }
    }

    enableMenu() {
        this.menuBtn.addEventListener('click', this.toggleMenu.bind(this));
        this.menuCloseBtn.addEventListener('click', this.toggleMenu.bind(this));
        document.addEventListener('click', this.outsideClick.bind(this));
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

// Inicializando o menu
document.addEventListener('DOMContentLoaded', () => {
    const menu = new Menu('#navPrincipal', '#menu', '#menuClose');
});
