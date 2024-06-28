document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const noticiaId = urlParams.get('id');

    if (noticiaId) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `assets/php/NoticiaPorId.php?id=${noticiaId}`, true);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                const response = JSON.parse(xhr.responseText);
                const noticia = response.Noticia;
                const conteudos = response.Conteudos;

                if (noticia && noticia.titulo) {
                    document.getElementById("tituloNoticia").innerText = noticia.titulo;
                }
                if (noticia && noticia.Resumo) {
                    document.getElementById("sinopseNoticia").innerText = noticia.Resumo;
                }
                if (noticia && noticia.ImagemCapa) {
                    document.getElementById("imagemCapa").src = noticia.ImagemCapa;
                }

                document.getElementById("nomeAutor").innerText = "Nome do autor: " + noticia.Autor;
                document.getElementById("DataDaPostagem").innerText = "Data da Postagem: " + noticia.DataPublicacao;
                document.getElementById("tema").innerText = "Categoria: " + noticia.Categoria;

                const conteudoNoticia = document.getElementById("conteudoNoticia");
                conteudoNoticia.innerHTML = '';

                conteudos.forEach(conteudo => {
                    if (conteudo.Imagem1) {
                        const img1 = document.createElement('img');
                        img1.src = conteudo.Imagem1;
                        conteudoNoticia.appendChild(img1);
                    }
                    if (conteudo.Text1) {
                        const text1 = document.createElement('p');
                        text1.innerText = conteudo.Text1;
                        conteudoNoticia.appendChild(text1);
                    }
                    if (conteudo.Imagem2) {
                        const img2 = document.createElement('img');
                        img2.src = conteudo.Imagem2;
                        conteudoNoticia.appendChild(img2);
                    }
                    if (conteudo.Text2) {
                        const text2 = document.createElement('p');
                        text2.innerText = conteudo.Text2;
                        conteudoNoticia.appendChild(text2);
                    }
                    if (conteudo.Imagem3) {
                        const img3 = document.createElement('img');
                        img3.src = conteudo.Imagem3;
                        conteudoNoticia.appendChild(img3);
                    }
                    if (conteudo.Text3) {
                        const text3 = document.createElement('p');
                        text3.innerText = conteudo.Text3;
                        conteudoNoticia.appendChild(text3);
                    }
                    if (conteudo.Imagem4) {
                        const img4 = document.createElement('img');
                        img4.src = conteudo.Imagem4;
                        conteudoNoticia.appendChild(img4);
                    }
                    if (conteudo.Text4) {
                        const text4 = document.createElement('p');
                        text4.innerText = conteudo.Text4;
                        conteudoNoticia.appendChild(text4);
                    }
                });
            } else {
                console.error(xhr.statusText);
            }
        };
        xhr.onerror = function () {
            console.error("Erro ao fazer a requisição.");
        };
        xhr.send();
    } else {
        console.error("ID da notícia não encontrado na URL.");
    }
});
