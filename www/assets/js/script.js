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
    interval = setInterval(nextSlide, 8000);
});

setaProxima.addEventListener('click', () => {
    nextSlide();
    clearInterval(interval);
    interval = setInterval(nextSlide, 8000);
});

interval = setInterval(() => {
    nextSlide();
    if (currentIndex === slide.length - 1) {
        setTimeout(() => {
            currentIndex = 0;
            atualizarSlider();
<<<<<<< HEAD
        }, 8000);
=======
        }, 5000); // Atraso para criar um efeito suave de rolagem infinita
>>>>>>> 0fdd71d52af9e7c31da933502cb2fa3ff0ad85d3
    }
}, 8000);


// const Servicos = document.querySelector('.servicos > h1');
// const AsideServicos = document.querySelector('.servicos > aside');

// Servicos.addEventListener('click', () => {
//     if (!AsideServicos.classList.contains('active')) {
//         AsideServicos.classList.add('active');
//     } else {
//         AsideServicos.classList.remove('active');
//     }
// });
