document.addEventListener('DOMContentLoaded', function () {
    MostrarAutores();
});

function MostrarAutores() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/php/autores.php', true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                var autores = JSON.parse(xhr.responseText);
                console.log('Autores recebidos:', autores);

                if (Array.isArray(autores)) {
                    const SectionPerfis = document.querySelector('section.perfis');
                    SectionPerfis.innerHTML = '';
                    autores.forEach(autor => {
                        var aside = document.createElement('aside');

                        aside.innerHTML =
                            '<button id="Deletar" data-id="' + autor.IdUsuario + '">X</button>' +
                            '<img src="' + autor.Imagem + '" >' +
                            '<span>' +
                            '<h2>' + autor.Nome + '</h2>' +
                            '<p>Cargo: ' + autor.Cargo + '</p>' +
                            '<p>Postagens: ' + autor.Postagens + ' postagens</p>' +
                            '<p>Última Postagem: ' + autor.UltimaPostagem + '</p>' +
                            '</span>';
                        SectionPerfis.appendChild(aside);

                        // Adiciona evento de click no botão de deletar
                        aside.querySelector('button#Deletar').addEventListener('click', function (e) {
                            var idAutor = e.target.getAttribute('data-id');
                            verificarPermissao(idAutor, function (permitido) {
                                if (permitido) {
                                    confirmacao(autor.Nome, function (confirmed) {
                                        if (confirmed) {
                                            DeletarAutor(idAutor);
                                        }
                                    });
                                } else {
                                    alert("Você não tem permissão para deletar este autor.");
                                }
                            });
                        });
                    });
                } else {
                    console.error('Esperava um array, mas recebeu:', typeof autores);
                }
            } catch (e) {
                console.error('Falha ao analisar JSON:', e);
            }
        } else {
            console.error('Falha na requisição. Status retornado: ' + xhr.status);
        }
    };
    xhr.onerror = function () {
        console.error('Erro ao fazer a requisição.');
    };
    xhr.send();
}

function verificarPermissao(idAutor, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/php/verificaPermissao.php?id=' + idAutor, true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            var resposta = JSON.parse(xhr.responseText);
            if (resposta.erro) {
                console.error(resposta.erro);
                callback(false);
            } else {
                const cargoUsuarioLogado = resposta.cargoUsuarioLogado;
                const cargoAutor = resposta.cargoAutor;

                let permitido = false;
                if (cargoUsuarioLogado === 'Administrador') {
                    permitido = true;
                } else if (cargoUsuarioLogado === 'Gestão' && cargoAutor === 'Autor') {
                    permitido = true;
                }

                callback(permitido);
            }
        } else {
            console.error("Falha na requisição. Status retornado: " + xhr.status);
            callback(false);
        }
    };
    xhr.onerror = function () {
        console.error("Erro ao fazer a requisição.");
        callback(false);
    };
    xhr.send();
}

function DeletarAutor(idAutor) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/php/delAutor.php?id=' + idAutor, true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                var resposta = JSON.parse(xhr.responseText);
                if (resposta.mensagem) {
                    console.log(resposta.mensagem);
                    // Atualiza a lista de autores
                    MostrarAutores();
                } else {
                    console.error(resposta.erro);
                }
            } catch (e) {
                console.error('Falha ao analisar JSON:', e);
            }
        } else {
            console.error('Falha na requisição. Status retornado: ' + xhr.status);
        }
    };
    xhr.onerror = function () {
        console.error('Erro ao fazer a requisição.');
    };
    xhr.send();
}

function confirmacao(NomeAutor, callback) {
    let confirmacao = document.querySelector("#confirmacao");
    const botaoConfirmar = document.getElementById('confirma');
    const botaoCancela = document.getElementById('cancelar');

    confirmacao.style.display = "flex";
    confirmacao.querySelector('p').textContent = `Você tem certeza que deseja deletar o autor ${NomeAutor}?`;

    function handleConfirm() {
        confirmacao.style.display = "none";
        callback(true); // Chama o callback com true quando confirmado
        removeEventListeners();
    }

    function handleCancel() {
        confirmacao.style.display = "none";
        callback(false); // Chama o callback com false quando cancelado
        removeEventListeners();
    }

    function removeEventListeners() {
        botaoConfirmar.removeEventListener('click', handleConfirm);
        botaoCancela.removeEventListener('click', handleCancel);
    }

    botaoConfirmar.addEventListener('click', handleConfirm);
    botaoCancela.addEventListener('click', handleCancel);
}
