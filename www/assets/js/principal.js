document.addEventListener("DOMContentLoaded", function () {
    carregarDashboard();
    carregarNoticias();
});

function carregarDashboard() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/php/Principal.php', true);
    xhr.onload = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    var infosPrincipal = JSON.parse(xhr.responseText);
                    atualizarPerfil(infosPrincipal);
                    atualizarDados(infosPrincipal);
                } catch (e) {
                    console.error("Falha ao analisar a resposta JSON para o dashboard: ", e);
                    console.error("Resposta recebida: ", xhr.responseText);
                }
            } else {
                console.error("Requisição do dashboard falhou com status: ", xhr.status);
            }
        }
    };
    xhr.send();
}

function atualizarPerfil(infosPrincipal) {
    var perfilH2s = document.querySelectorAll('.perfil h2');
    var perfilPs = document.querySelectorAll('.perfil p');
    var perfilImg = document.querySelector('.perfil aside img');
    
    perfilImg.src = infosPrincipal.ImagemUsuario;

    if (perfilH2s.length > 1) {
        perfilH2s[0].textContent = "Autor: " + infosPrincipal.nomePessoal;
        perfilH2s[1].textContent = "Nome de Usuário: " + infosPrincipal.nomeUsuario;
    }

    if (perfilPs.length > 1) {
        perfilPs[0].textContent = "Postagens: " + infosPrincipal.nPostagensAutor;
        perfilPs[1].textContent = "Última Postagem: " + infosPrincipal.uPostagemAutor;
    }
}

function atualizarDados(infosPrincipal) {
    var dadosAsides = document.querySelectorAll('.dados aside');

    if (dadosAsides.length > 3) {
        dadosAsides[0].querySelector('p').textContent = infosPrincipal.NumeroDePostagens;
        dadosAsides[1].querySelector('p').textContent = infosPrincipal.NumeroDeAutores;
        dadosAsides[2].querySelector('p.FraseGrande').textContent = infosPrincipal.MaisPostou;
        dadosAsides[3].querySelector('p.FraseGrande').textContent = infosPrincipal.CategoriaMaisPostada;
    }
}

function carregarNoticias() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/php/ultimasNoticiasPrincipal.php', true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                var resultados = JSON.parse(xhr.responseText);
                atualizarNoticias(resultados);
            } catch (e) {
                console.error("Falha ao analisar a resposta JSON para as notícias: ", e);
                console.error("Resposta recebida: ", xhr.responseText);
            }
        } else {
            console.error("Requisição de notícias falhou com status: ", xhr.status);
        }
    };
    xhr.onerror = function () {
        console.error('Erro ao fazer a requisição.');
    };
    xhr.send();
}

function atualizarNoticias(resultados) {
    var articleNoticias = document.getElementById('CardsPostagens');
    if (articleNoticias) {
        articleNoticias.innerHTML = '';
        resultados.map(criarElementoNoticia).forEach(function (elemento) {
            articleNoticias.appendChild(elemento);
        });
    } else {
        console.error("Elemento com id 'CardsPostagens' não encontrado.");
    }
}

function criarElementoNoticia(noticia) {
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
}
