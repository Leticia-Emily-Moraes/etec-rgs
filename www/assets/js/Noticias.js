document.addEventListener("DOMContentLoaded", function () {
  const sliders = document.querySelectorAll(".slider");
  const setasAnteriores = document.querySelectorAll(".SetaAnterior");
  const setasProximas = document.querySelectorAll(".SetaProxima");
  const buttonLerMais = document.getElementById("verMais");
  const categorias = document.querySelectorAll("input[type=radio]");
  const termoPesquisaInput = document.getElementById("termoPesquisa");
  let currentPage = 1;

  // Carregar notícias iniciais
  carregarUltimasNoticias();
  carregarMaisNoticias(currentPage);

  // Eventos
  termoPesquisaInput.addEventListener("input", handlePesquisaInput);
  buttonLerMais.addEventListener("click", handleVerMaisClick);
  categorias.forEach((categoria) =>
    categoria.addEventListener("click", handleCategoriaClick)
  );

  // Inicializar sliders
  sliders.forEach((slider, index) => inicializarSlider(slider, index));

  // Funções de manipulação de eventos
  function handlePesquisaInput() {
    const termoPesquisa = termoPesquisaInput.value.trim();
    if (termoPesquisa !== "") {
      carregarNoticiasPorPesquisa(termoPesquisa);
    } else {
      mostrarNoticiasPadrao();
    }
  }

  function handleVerMaisClick() {
    currentPage++;
    carregarMaisNoticias(currentPage);
  }

  function handleCategoriaClick(event) {
    const tema = event.target.value;
    carregarNoticiaPeloTema(tema);
  }

  // Funções de carregamento de notícias
  function carregarUltimasNoticias() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "assets/php/ultimasNoticias.php", true);
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        const ultimasNoticias = JSON.parse(xhr.responseText);
        const spansNoticias = document.querySelectorAll("#UltimasNoticias span");
        spansNoticias.forEach((span) => (span.innerHTML = ""));
        ultimasNoticias.forEach((noticia, index) => {
          if (spansNoticias[index]) {
            spansNoticias[index].innerHTML = criarCardNoticia(noticia);
          }
        });
        adicionarEventoCliqueNoticias();
      } else {
        console.error(xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Erro ao fazer a requisição.");
    };
    xhr.send();
  }

  function carregarMaisNoticias(page) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `assets/php/TodasAsNoticia.php?page=${page}`, true);
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        const maisNoticias = JSON.parse(xhr.responseText);
        const containerNoticias = document.getElementById("ContainerTodas");
        maisNoticias.forEach((noticia) => {
          const span = document.createElement("span");
          span.innerHTML = criarCardNoticia(noticia);
          containerNoticias.appendChild(span);
        });
        adicionarEventoCliqueNoticias();
      } else {
        console.error(xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Erro ao fazer a requisição.");
    };
    xhr.send();
  }

  function carregarNoticiasPorPesquisa(termoPesquisa) {
    currentPage = 1; // Reinicia currentPage para nova pesquisa
    const xhr = new XMLHttpRequest();
    xhr.open(
      "GET",
      `assets/php/NoticiasPorPesquisa.php?termo=${termoPesquisa}&page=${currentPage}`,
      true
    );
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        const noticias = JSON.parse(xhr.responseText);
        const containerNoticias = document.querySelector("section.Noticias");
        const articlesExistentes = document.querySelectorAll(
          ".Noticias > article"
        );
        articlesExistentes.forEach(
          (article) => (article.style.display = "none")
        );
        const articleNovo = document.createElement("article");
        articleNovo.classList.add("novo");
        articleNovo.innerHTML = `
                    <h1>Resultados para "${termoPesquisa}"</h1>
                    <hr>
                    <p>Notícias relacionadas ao termo "${termoPesquisa}"</p>
                    <aside class="containerVerMais"></aside>`;
        if (containerNoticias) {
          containerNoticias.appendChild(articleNovo);
          const containerVerMais =
            articleNovo.querySelector(".containerVerMais");
          noticias.forEach((noticia) => {
            const span = document.createElement("span");
            span.innerHTML = criarCardNoticia(noticia);
            containerVerMais.appendChild(span);
          });
          adicionarEventoCliqueNoticias();
        } else {
          console.error(
            `Contêiner de notícias para o termo "${termoPesquisa}" não encontrado.`
          );
          articlesExistentes.forEach(
            (article) => (article.style.display = "flex")
          );
        }
      } else {
        console.error(xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Erro ao fazer a requisição.");
    };
    xhr.send();
  }

  function mostrarNoticiasPadrao() {
    const articlesExistentes = document.querySelectorAll(".Noticias > article");
    document
      .querySelectorAll(".Noticias > article.novo")
      .forEach((article) => article.remove());
    articlesExistentes.forEach((article) => (article.style.display = "flex"));
  }

  function carregarNoticiaPeloTema(tema) {
    currentPage = 1; // Reinicia currentPage para novo tema
    const xhr = new XMLHttpRequest();
    xhr.open(
      "GET",
      `assets/php/NoticiasPorTema.php?categoria=${tema}&page=${currentPage}`,
      true
    );
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        const noticiasPorTema = JSON.parse(xhr.responseText);
        const containerNoticias = document.querySelector("section.Noticias");
        const articlesExistentes = document.querySelectorAll(
          ".Noticias > article"
        );
        articlesExistentes.forEach(
          (article) => (article.style.display = "none")
        );
        if (tema !== "Todas") {
          const articleNovo = document.createElement("article");
          articleNovo.classList.add("novo");
          articleNovo.innerHTML = `
                        <h1>Categoria "${tema}"</h1>
                        <hr>
                        <p>Notícias da categoria "${tema}"</p>
                        <aside class="containerVerMais"></aside>
                        <button id="verMaisTema" class="ver-mais">Ver Mais</button>`;
          if (containerNoticias) {
            containerNoticias.appendChild(articleNovo);
            const containerVerMais =
              articleNovo.querySelector(".containerVerMais");
            noticiasPorTema.forEach((noticia) => {
              const span = document.createElement("span");
              span.innerHTML = criarCardNoticia(noticia);
              containerVerMais.appendChild(span);
            });
            document
              .getElementById("verMaisTema")
              .addEventListener("click", function () {
                currentPage++;
                carregarMaisNoticiasPorTema(tema, currentPage);
              });
            adicionarEventoCliqueNoticias();
          } else {
            console.error(
              `Contêiner de notícias para o tema "${tema}" não encontrado.`
            );
          }
        } else {
          // Exibe os artigos padrão e remove artigos carregados dinamicamente
          mostrarNoticiasPadrao();
        }
      } else {
        console.error(xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Erro ao fazer a requisição.");
    };
    xhr.send();
  }

  function carregarMaisNoticiasPorTema(tema, page) {
    const xhr = new XMLHttpRequest();
    xhr.open(
      "GET",
      `assets/php/NoticiasPorTema.php?categoria=${tema}&page=${page}`,
      true
    );
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        const noticiasPorTema = JSON.parse(xhr.responseText);
        const container = document.querySelector(".containerVerMais");
        if (!container) {
          console.error("Slider de notícias não encontrado.");
          return;
        }
        noticiasPorTema.forEach((noticia) => {
          const span = document.createElement("span");
          span.innerHTML = criarCardNoticia(noticia);
          container.appendChild(span);
        });
        adicionarEventoCliqueNoticias();
      } else {
        console.error(xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Erro ao fazer a requisição.");
    };
    xhr.send();
  }

  // Funções de criação de elementos
  function criarCardNoticia(noticia) {
    return `
      <div class="noticia" data-id="${noticia.ID}">
        <div class="title-img">
          <h1>${noticia.Titulo}</h1>
          <img src="${noticia.ImagemCapa}">
        </div>
        <div class="content-noticia">
          <span class="separador">
            <p>${noticia.Categoria}</p>
            <p>${noticia.DataPublicacao}</p>
          </span>
          <p>${noticia.Resumo}</p>
          <p>Autor: ${noticia.Autor}</p>
        </div>
      </div>`;
  }

  function adicionarEventoCliqueNoticias() {
    const noticias = document.querySelectorAll(".noticia");
    noticias.forEach(noticia => {
      noticia.addEventListener("click", function () {
        const noticiaId = this.getAttribute("data-id");
        window.location.href = `noticia.html?id=${noticiaId}`;
      });
    });
  }

  // Funções de inicialização dos sliders
  function inicializarSlider(slider, index) {
    const slides = slider.querySelectorAll("span");
    let numSlides = slides.length;
    if(window.innerWidth > 767){
      numSlides = slides.length - 1;
    }
    let currentIndex = 0;

    function atualizarSlider() {
      if (slides[currentIndex]) {
        const slideWidth = slides[currentIndex].offsetWidth;
        const translateValue = -currentIndex * (slideWidth + 20);
        slider.style.transform = `translateX(${translateValue}px)`;
      }
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % numSlides;
      atualizarSlider();
    }

    function prevSlide() {
      currentIndex = (currentIndex - 1 + numSlides) % numSlides;
      atualizarSlider();
    }

    if (setasAnteriores[index] && setasProximas[index]) {
      setasAnteriores[index].addEventListener("click", prevSlide);
      setasProximas[index].addEventListener("click", nextSlide);
    } else {
      console.error("Botões não encontrados no DOM");
    }
  }
});
