var nav = document.querySelector('navPrincipal');
var menuBtn = document.querySelector('MenuHam');

function MostrarMenu() {
    if (nav.classList.contains('open')) {
        nav.classList.remove('open');
        container.style.zIndex = 0;
    } else {
        nav.classList.add('open');
    }
}
menuBtn.addEventListener('click', () => {
    MostrarMenu();
});
