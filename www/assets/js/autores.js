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
                console.log('Autores recebidos:', autores); // Log dos dados recebidos

                if (Array.isArray(autores)) {
                    const SectionPerfis = document.querySelector('section.perfis');
                    SectionPerfis.innerHTML = '';
                    autores.forEach(autor => {
                        var aside = document.createElement('aside');

                        aside.innerHTML = 
                        '<img src="'+ autor.Imagem +'" >'+
                        '<span>'+
                            '<h2>'+ autor.Nome +'</h2>'+
                            '<p>Cargo: '+ autor.Cargo +'</p>'+
                            '<p>Postagens: '+ autor.Postagens +'  postagens</p>'+
                            '<p>Ultima Postagem: '+ autor.UltimaPostagem +'</p>'+
                        '</span>';
                        SectionPerfis.appendChild(aside);
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
    xhr.onerror = function() {
        console.error('Erro ao fazer a requisição.');
    };
    xhr.send();
}
