document.addEventListener("DOMContentLoaded", function () {
    carregarDashboard();
    CarregarNoticias();
})

function carregarDashboard() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var infosPrincipal = JSON.parse(xhr.responseText);

            document.querySelector('.perfil h2:nth-of-type(1)').textContent = "Autor: " + infosPrincipal.nomePessoal;
            document.querySelector('.perfil h2:nth-of-type(2)').textContent = "Nome de Usuário: " + infosPrincipal.nomeUsuario;
            document.querySelector('.perfil p:nth-of-type(1)').textContent = "Postagens: " + infosPrincipal.nPostagensAutor;
            document.querySelector('.perfil p:nth-of-type(2)').textContent = "Ultima Postagem: " + infosPrincipal.uPostagemAutor;
            document.querySelector('.dados aside:nth-of-type(1) p').textContent = infosPrincipal.NumeroDePostagens;
            document.querySelector('.dados aside:nth-of-type(2) p').textContent = infosPrincipal.NumeroDeAutores;
            document.querySelector('.dados aside:nth-of-type(3) p.FraseGrande').textContent = infosPrincipal.MaisPostou;
            document.querySelector('.dados aside:nth-of-type(4) p.FraseGrande').textContent = infosPrincipal.CategoriaMaisPostada;
        }
    }
    xhr.open('GET', '../Postagens/Principal.php', true);
    xhr.send();
}

function CarregarNoticias() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../Postagens/ultimasNoticiasPrincipal.php', true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Parse JSON response
            var resultados = JSON.parse(xhr.responseText);
            // Criar o mapa de notícias usando a função map()
            var articleNoticias = document.getElementById('CardsPostagens');
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
            console.error(xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Erro ao fazer a requisição.');
    };
    xhr.send();
}