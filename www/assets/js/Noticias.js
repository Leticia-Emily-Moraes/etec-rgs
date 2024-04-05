document.addEventListener('DOMContentLoaded', function () {
    if (window.innerWidth < 1023) {
        const sliders = document.querySelectorAll('.slider');
        const setasAnteriores = document.querySelectorAll('.SetaAnterior');
        const setasProximas = document.querySelectorAll('.SetaProxima');

        sliders.forEach((slider, index) => {
            const slides = slider.querySelectorAll('span');
            let numSlides = slides.length;
            if (window.innerWidth > 767) {
                numSlides = slides.length - 1;
            }
            let currentIndex = 0;
            let interval;



            function atualizarSlider() {
                const slideWidth = slides[currentIndex].offsetWidth;
                const translateValue = -currentIndex * (slideWidth + 20);
                slider.style.transform = `translateX(${translateValue}px)`;
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % numSlides;
                atualizarSlider();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + numSlides) % numSlides;
                atualizarSlider();
            }

            setasAnteriores[index].addEventListener('click', () => {
                prevSlide();
                clearInterval(interval);
                interval = setInterval(nextSlide, 5000);
            });

            setasProximas[index].addEventListener('click', () => {
                nextSlide();
                clearInterval(interval);
                interval = setInterval(nextSlide, 5000);
            });

            interval = setInterval(() => {
                nextSlide();
                if (currentIndex === slides.length - 1) {
                    setTimeout(() => {
                        currentIndex = 0;
                        atualizarSlider();
                    }, 1000);
                }
            }, 5000);
        });
    }
});
