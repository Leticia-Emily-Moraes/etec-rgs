const slider = document.getElementById("slider");
let slide = document.querySelectorAll(".slides");
const setaAnterior = document.getElementById("SetaAnterior");
const setaProxima = document.getElementById("SetaProxima");
let currentIndex = 0;
let interval;

carregarNoticiasSlider();
carregarUltimasNoticias();

function atualizarSlider() {
  const slideWidth = slide[0].clientWidth;
  slider.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
}

function nextSlide() {
  currentIndex = (currentIndex + 1) % slide.length;
  atualizarSlider();
}

function prevSlide() {
  currentIndex = (currentIndex - 1 + slide.length) % slide.length;
  atualizarSlider();
}

function resetInterval() {
  clearInterval(interval);
  interval = setInterval(nextSlide, 8000);
}

setaAnterior.addEventListener("click", () => {
  prevSlide();
  resetInterval();
});

setaProxima.addEventListener("click", () => {
  nextSlide();
  resetInterval();
});

interval = setInterval(() => {
  nextSlide();
  if (currentIndex === slide.length - 1) {
    setTimeout(() => {
      currentIndex = 0;
      atualizarSlider();
    }, 8000);
  }
}, 8000);

function criarCardNoticiaSlides(noticia) {
  return `
  <article class="slides" data-id="${noticia.ID}">
    <span>
        <h1>${noticia.Titulo}</h1>
        <p>${noticia.Resumo}</p>
    </span>
    <img src="${noticia.ImagemCapa}" alt=""/>
  </article>`;
}

function carregarNoticiasSlider() {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "assets/php/SliderIndex.php", true);
  xhr.onload = function () {
    if (xhr.status >= 200 && xhr.status < 300) {
      const ultimasNoticias = JSON.parse(xhr.responseText);
      const SectionSlider = document.querySelector("#slider");
      SectionSlider.innerHTML = "";
      ultimasNoticias.forEach((noticia) => {
        SectionSlider.innerHTML += criarCardNoticiaSlides(noticia);
      });
      slide = document.querySelectorAll(".slides"); 
      adicionarEventoCliqueSlides();
    } else {
      console.error(xhr.statusText);
    }
  };
  xhr.onerror = function () {
    console.error("Erro ao fazer a requisição.");
  };
  xhr.send();
}

function adicionarEventoCliqueSlides() {
  const noticias = document.querySelectorAll(".slides");
  noticias.forEach(noticia => {
    noticia.addEventListener("click", function () {
      const noticiaId = this.getAttribute("data-id");
      window.location.href = `noticia.html?id=${noticiaId}`;
    });
  });
}


function criarCardUltimasNoticia(noticia) {
    return `
    <aside class="BlocoNoticia" data-id="${noticia.ID}">
            <div class="title-img">
              <img
                src="${noticia.ImagemCapa}"
                alt="Imagem da capa"
              />
              <h1>${noticia.Titulo}</h1>
            </div>
            <div class="content-noticia">
              <p>
                ${noticia.Resumo}
              </p>
              <p>${noticia.Autor}</p>
              <span class="separador">
                <p>${noticia.Categoria}</p>
                <p>${noticia.DataPublicacao}</p>
              </span>
            </div>
          </aside>`;
  }
  
  function carregarUltimasNoticias() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "assets/php/noticiasSegundariasDoIndex.php", true);
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        const ultimasNoticias = JSON.parse(xhr.responseText);
        const articleSepara = document.querySelector(".separa");
        articleSepara.innerHTML = "";
        ultimasNoticias.forEach((noticia) => {
          articleSepara.innerHTML += criarCardUltimasNoticia(noticia);
        });
        slide = document.querySelectorAll(".slides"); 
        adicionarEventoCliqueBlocoNoticia();
      } else {
        console.error(xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Erro ao fazer a requisição.");
    };
    xhr.send();
  }
  
  function adicionarEventoCliqueBlocoNoticia() {
    const noticias = document.querySelectorAll(".BlocoNoticia");
    noticias.forEach(noticia => {
      noticia.addEventListener("click", function () {
        const noticiaId = this.getAttribute("data-id");
        window.location.href = `noticia.html?id=${noticiaId}`;
      });
    });
  }