class Menu {
    constructor(menuSelector, menuBtnSelector, menuCloseBtnSelector, header) {
        this.menu = document.querySelector(menuSelector);
        this.menuBtn = document.querySelector(menuBtnSelector);
        this.menuCloseBtn = document.querySelector(menuCloseBtnSelector);
        this.header = document.querySelector(header);
        this.outsideClick = this.outsideClick.bind(this);
        this.toggleMenu = this.toggleMenu.bind(this);
        this.menuBtn.addEventListener('click', this.toggleMenu);
        this.menuCloseBtn.addEventListener('click', this.toggleMenu);
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
        isOpen ? this.closeMenu() : this.openMenu();
    }

    openMenu() {
        this.menu.classList.add('open');
        this.menuBtn.style.display = 'none';
        this.menuCloseBtn.style.display = 'block';
        this.header.style.background = '#273c75'
        document.body.style.overflow = 'hidden';

    }

    closeMenu() {
        this.menu.classList.remove('open');
        this.menuBtn.style.display = 'block';
        this.menuCloseBtn.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const menu = new Menu('#navPrincipal', '#menu', '#menuClose', '#header');

    const header = document.getElementById('header')
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            preloader.remove();
        });
    }

    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        const toggleBackToTop = () => {
            backToTop.classList.toggle('active', window.scrollY >= 100);
        };
        backToTop.addEventListener('click', () => {
            // Rolagem suave para o topo
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        window.addEventListener('scroll', function(){
            toggleBackToTop();
            if(window.scrollY >= 100){
                header.classList.add('rolagem');
            }else{
                header.classList.remove('rolagem');
            }

        });
    }

});
