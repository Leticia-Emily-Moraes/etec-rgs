document.addEventListener('DOMContentLoaded', function () {
    const sliders = document.querySelectorAll('.slider');
    const setasAnteriores = document.querySelectorAll('.SetaAnterior');
    const setasProximas = document.querySelectorAll('.SetaProxima');
    const ButtonLerMais = document.getElementById('verMais');
    const categorias = document.querySelectorAll('input[type=radio]')
    let currentPage = 1;

    CarregarUltimasNoticias();
    CarregarMaisNoticias(currentPage);

    const termoPesquisaInput = document.getElementById('termoPesquisa');

    termoPesquisaInput.addEventListener('input', function () {
        const termoPesquisa = termoPesquisaInput.value.trim();
        if (termoPesquisa !== '') {
            CarregarNoticiasPorPesquisa(termoPesquisa);
        }
    });

    function CarregarNoticiasPorPesquisa(termoPesquisa) {
        currentPage = 1; // Reset currentPage for new search
        var xhr = new XMLHttpRequest();
        xhr.open('GET', `assets/php/NoticiasPorPesquisa.php?termo=${termoPesquisa}&page=${currentPage}`, true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Parse JSON response and display results
                var containerNoticias = document.querySelector('section.Noticias');
                const articlesExistentes = document.querySelectorAll('.Noticias > article');

                // Remove previously created new articles
                document.querySelectorAll('.Noticias > article.novo').forEach(article => article.remove());

                var articleNovo = document.createElement('article');
                articleNovo.classList.add('novo');
                articleNovo.innerHTML =
                    '<h1>Categoria "' + tema + '"</h1>' +
                    '<hr>' +
                    '<p>Notícias da categoria "' + tema + '"</p>' +
                    '<aside class="containerVerMais"></aside>' +
                    '<button id="verMaisTema" class="ver-mais">Ver Mais</button>'; // Adiciona o botão "Ver Mais"

                if (containerNoticias) {
                    containerNoticias.appendChild(articleNovo);

                    // Preenche os spans com as notícias filtradas por tema
                    var containerVerMais = articleNovo.querySelector('.containerVerMais');
                    NoticiasPorTema.forEach(function (noticia) {
                        var span = document.createElement('span');
                        span.innerHTML =
                            '<div class="title-img">' +
                            '<h1>' + noticia.Titulo + '</h1>' +
                            '<img src="' + noticia.ImagemCapa + '">' +
                            '</div>' +
                            '<div class="content-noticia">' +
                            '<span class="separador">' +
                            '<p>' + noticia.Categoria + '</p>' +
                            '<p>' + noticia.DataPublicacao + '</p>' +
                            '</span>' +
                            '<p>' + noticia.Resumo + '</p>' +
                            '<p>Autor: ' + noticia.Autor + '</p>' +
                            '</div>';
                        containerVerMais.appendChild(span);
                    });

                    // Adiciona evento de clique ao botão "Ver Mais"
                    document.getElementById('verMaisTema').addEventListener('click', function () {
                        currentPage++;
                        CarregarMaisNoticiasPorTema(tema, currentPage);
                    });
                } else {
                    console.error(`Contêiner de notícias para o tema "${tema}" não encontrado.`);
                }
            } else {
                console.error(xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Erro ao fazer a requisição.');
        };

        xhr.send();
    }

    ButtonLerMais.addEventListener('click', function () {
        currentPage++;
        CarregarMaisNoticias(currentPage);
    });

    categorias.forEach(function (categoria, index) {
        categoria.addEventListener('click', function () {
            CarregarNoticiaPeloTema(categoria.value);
        });
    });

    sliders.forEach((slider, index) => {
        const slides = slider.querySelectorAll('span');
        let numSlides = slides.length - 1;
        if (window.innerWidth > 767) {
            numSlides = slides.length - 1;
        }
        let currentIndex = 0;

        function atualizarSlider() {
            if (slides[currentIndex]) {
                const slideWidth = slides[currentIndex].offsetWidth;
                const translateValue = -currentIndex * (slideWidth + 10);
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

        // Check if buttons exist before attaching event listeners
        if (setasAnteriores[index] && setasProximas[index]) {
            setasAnteriores[index].addEventListener('click', () => {
                prevSlide();
            });

            setasProximas[index].addEventListener('click', () => {
                nextSlide();
            });
        } else {
            console.error("Buttons not found in the DOM");
        }
    });

    function CarregarUltimasNoticias() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'assets/php/ultimasNoticias.php', true);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Parse JSON response
                var ultimasNoticias = JSON.parse(xhr.responseText);
                var spansNoticias = document.querySelectorAll('#UltimasNoticias span');

                // Limpa o conteúdo dos spans
                spansNoticias.forEach(function (span) {
                    span.innerHTML = '';
                });

                // Preenche os spans com as últimas notícias
                ultimasNoticias.forEach(function (noticia, index) {
                    if (spansNoticias[index]) { // Verifica se o span correspondente existe
                        spansNoticias[index].innerHTML =
                            '<div class="title-img">' +
                            '<h1>' + noticia.Titulo + '</h1>' +
                            '<img src="' + noticia.ImagemCapa + '">' +
                            '</div>' +
                            '<div class="content-noticia">' +
                            '<span class="separador">' +
                            '<p>' + noticia.Categoria + '</p>' +
                            '<p>' + noticia.DataPublicacao + '</p>' +
                            '</span>' +
                            '<p>' + noticia.Resumo + '</p>' +
                            '<p>Autor: ' + noticia.Autor + '</p>' +
                            '</div>';
                    }
                });
            } else {
                console.error(xhr.statusText);
            }
        };
        xhr.onerror = function () {
            console.error('Erro ao fazer a requisição.');
        };
        xhr.send();
    }

    function CarregarMaisNoticias(page) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', `assets/php/TodasAsNoticia.php?page=${page}`, true);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Parse JSON response
                var maisNoticias = JSON.parse(xhr.responseText);
                var containerNoticias = document.getElementById('ContainerTodas');

                // Adiciona novas notícias ao contêiner
                maisNoticias.forEach(function (noticia) {
                    var span = document.createElement('span');
                    span.innerHTML =
                        '<div class="title-img">' +
                        '<h1>' + noticia.Titulo + '</h1>' +
                        '<img src="' + noticia.ImagemCapa + '">' +
                        '</div>' +
                        '<div class="content-noticia">' +
                        '<span class="separador">' +
                        '<p>' + noticia.Categoria + '</p>' +
                        '<p>' + noticia.DataPublicacao + '</p>' +
                        '</span>' +
                        '<p>' + noticia.Resumo + '</p>' +
                        '<p>Autor: ' + noticia.Autor + '</p>' +
                        '</div>';
                    containerNoticias.appendChild(span);
                });
            } else {
                console.error(xhr.statusText);
            }
        };
        xhr.onerror = function () {
            console.error('Erro ao fazer a requisição.');
        };
        xhr.send();
    }

    function CarregarNoticiaPeloTema(tema) {
        currentPage = 1; // Reset currentPage for new tema
        var xhr = new XMLHttpRequest();
        xhr.open('GET', `assets/php/NoticiasPorTema.php?categoria=${tema}&page=${currentPage}`, true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Parse JSON response
                var NoticiasPorTema = JSON.parse(xhr.responseText);
                var containerNoticias = document.querySelector('section.Noticias');
                const articlesExistentes = document.querySelectorAll('.Noticias > article');

                // Remove previously created new articles
                document.querySelectorAll('.Noticias > article.novo').forEach(article => article.remove());

                if (tema !== 'Todas') {
                    articlesExistentes.forEach(article => {
                        article.style.display = 'none';
                    });

                    var articleNovo = document.createElement('article');
                    articleNovo.classList.add('novo');
                    articleNovo.innerHTML =
                        '<h1>Categoria "' + tema + '"</h1>' +
                        '<hr>' +
                        '<p>Notícias da categoria "' + tema + '"</p>' +
                        '<aside class="containerVerMais"></aside>' +
                        '<button id="verMaisTema" class="ver-mais">Ver Mais</button>'; // Adiciona o botão "Ver Mais"

                    if (containerNoticias) {
                        containerNoticias.appendChild(articleNovo);

                        // Preenche os spans com as notícias filtradas por tema
                        var containerVerMais = articleNovo.querySelector('.containerVerMais');
                        NoticiasPorTema.forEach(function (noticia) {
                            var span = document.createElement('span');
                            span.innerHTML =
                                '<div class="title-img">' +
                                '<h1>' + noticia.Titulo + '</h1>' +
                                '<img src="' + noticia.ImagemCapa + '">' +
                                '</div>' +
                                '<div class="content-noticia">' +
                                '<span class="separador">' +
                                '<p>' + noticia.Categoria + '</p>' +
                                '<p>' + noticia.DataPublicacao + '</p>' +
                                '</span>' +
                                '<p>' + noticia.Resumo + '</p>' +
                                '<p>Autor: ' + noticia.Autor + '</p>' +
                                '</div>';
                            containerVerMais.appendChild(span);
                        });

                        // Adiciona evento de clique ao botão "Ver Mais"
                        document.getElementById('verMaisTema').addEventListener('click', function () {
                            currentPage++;
                            CarregarMaisNoticiasPorTema(tema, currentPage);
                        });
                    } else {
                        console.error(`Contêiner de notícias para o tema "${tema}" não encontrado.`);
                    }
                } else {
                    articlesExistentes.forEach(article => {
                        article.style.display = 'flex';
                    });
                }
            } else {
                console.error(xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Erro ao fazer a requisição.');
        };

        xhr.send();
    }

    function CarregarMaisNoticiasPorTema(tema, page) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', `assets/php/NoticiasPorTema.php?categoria=${tema}&page=${page}`, true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Parse JSON response
                var NoticiasPorTema = JSON.parse(xhr.responseText);
                var container = document.querySelector('.containerVerMais');
                if (!container) {
                    console.error('Slider de notícias não encontrado.');
                    return;
                }

                // Adiciona novas notícias ao slider
                NoticiasPorTema.forEach(function (noticia) {
                    var span = document.createElement('span');
                    span.innerHTML =
                        '<div class="title-img">' +
                        '<h1>' + noticia.Titulo + '</h1>' +
                        '<img src="' + noticia.ImagemCapa + '">' +
                        '</div>' +
                        '<div class="content-noticia">' +
                        '<span class="separador">' +
                        '<p>' + noticia.Categoria + '</p>' +
                        '<p>' + noticia.DataPublicacao + '</p>' +
                        '</span>' +
                        '<p>' + noticia.Resumo + '</p>' +
                        '<p>Autor: ' + noticia.Autor + '</p>' +
                        '</div>';
                    container.appendChild(span);
                });
            } else {
                console.error(xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Erro ao fazer a requisição.');
        };

        xhr.send();
    }

});
