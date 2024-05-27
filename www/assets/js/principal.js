document.addEventListener("DOMContentLoaded", function () {
    carregarDashboard();
    CarregarNoticias();
});

function carregarDashboard() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    var infosPrincipal = JSON.parse(xhr.responseText);

                    var perfilH2s = document.querySelectorAll('.perfil h2');
                    var perfilPs = document.querySelectorAll('.perfil p');
                    var dadosAsides = document.querySelectorAll('.dados aside');

                    if (perfilH2s.length > 1) {
                        perfilH2s[0].textContent = "Autor: " + infosPrincipal.nomePessoal;
                        perfilH2s[1].textContent = "Nome de Usuário: " + infosPrincipal.nomeUsuario;
                    }
                    if (perfilPs.length > 1) {
                        perfilPs[0].textContent = "Postagens: " + infosPrincipal.nPostagensAutor;
                        perfilPs[1].textContent = "Última Postagem: " + infosPrincipal.uPostagemAutor;
                    }
                    if (dadosAsides.length > 3) {
                        dadosAsides[0].querySelector('p').textContent = infosPrincipal.NumeroDePostagens;
                        dadosAsides[1].querySelector('p').textContent = infosPrincipal.NumeroDeAutores;
                        dadosAsides[2].querySelector('p.FraseGrande').textContent = infosPrincipal.MaisPostou;
                        dadosAsides[3].querySelector('p.FraseGrande').textContent = infosPrincipal.CategoriaMaisPostada;
                    }
                } catch (e) {
                    console.error("Failed to parse JSON response: ", e);
                    console.error("Response received: ", xhr.responseText);
                }
            } else {
                console.error("Request failed with status: ", xhr.status);
            }
        }
    };
    xhr.open('GET', 'assets/php/Principal.php', true);
    xhr.send();
}

function CarregarNoticias() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/php/ultimasNoticiasPrincipal.php', true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                // Parse JSON response
                var resultados = JSON.parse(xhr.responseText);

                // Get the element to append news articles
                var articleNoticias = document.getElementById('CardsPostagens');

                if (articleNoticias) {
                    // Clear existing content
                    articleNoticias.innerHTML = '';

                    // Create news cards using map() and append them to the container
                    resultados.map(function (noticia) {
                        var asideNoticia = document.createElement('aside');
                        asideNoticia.innerHTML =
                            '<div class="title-img">' +
                            '<h1>' + noticia.Titulo + '</h1>' +
                            '<img src="' + noticia.ImagemCapa + '">' +
                            '</div>' +
                            '<div class="content-noticia">' +
                            '<span>' +
                            '<p>' + noticia.Categoria + '</p>' +
                            '<p>' + noticia.DataPublicacao + '</p>' +
                            '</span>' +
                            '<p>' + noticia.Resumo + '</p>' +
                            '<p>Autor: ' + noticia.Autor + '</p>' +
                            '</div>';
                        return asideNoticia;
                    }).forEach(function (elemento) {
                        articleNoticias.appendChild(elemento);
                    });
                } else {
                    console.error("Element with id 'CardsPostagens' not found.");
                }
            } catch (e) {
                console.error("Failed to parse JSON response: ", e);
                console.error("Response received: ", xhr.responseText);
            }
        } else {
            console.error("Request failed with status: ", xhr.status);
        }
    };
    xhr.onerror = function () {
        console.error('Erro ao fazer a requisição.');
    };
    xhr.send();
}
