document.addEventListener('DOMContentLoaded', function () {
    adicionarAutor();

    const inputImg = document.querySelector("#img");
    const PreImagem = document.querySelector(".ImagemPreview");
    const imagemTxt = "Coloque a Imagem";
    PreImagem.innerHTML = imagemTxt;

    inputImg.addEventListener("change", function (e) {
        const Mudanca = e.target;
        const arquivo = Mudanca.files[0];

        if (arquivo) {
            const leitor = new FileReader();
            leitor.readAsDataURL(arquivo);
            leitor.addEventListener("load", function (e) {
                const imagemCarregada = e.target;
                const imagem = document.createElement('img');
                imagem.src = imagemCarregada.result;
                imagem.classList.add('ImgViewer');

                PreImagem.innerHTML = "";
                PreImagem.appendChild(imagem);

                localStorage.setItem('imagemCapa', imagemCarregada.result);
            });
        } else {
            PreImagem.innerHTML = imagemTxt;
        }
    });
});

function adicionarAutor() {
    const form = document.getElementById('postForm');
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const NomeCompleto = document.getElementById('NomeCompleto').value;
        const NomeUser = document.getElementById('NomeUser').value;
        const SenhaUser = document.getElementById('SenhaUser').value;
        const Cargo = document.getElementById('Cargo').value;
        const imagem = document.getElementById('img').files[0];

        if (!NomeCompleto || !NomeUser || !SenhaUser || !Cargo || !imagem) {
            alert("Por favor, preencha todos os campos.");
            return;
        }

        if (imagem.size > 5 * 1024 * 1024) {
            alert("Desculpe, o arquivo é muito grande (limite de 5MB).");
            return;
        }

        const extensaoValida = ['jpg', 'jpeg', 'png', 'gif'];
        const extensao = imagem.name.split('.').pop().toLowerCase();
        if (!extensaoValida.includes(extensao)) {
            alert("Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.");
            return;
        }

        const tituloFormatado = formatarTitulo(NomeCompleto);

        const formData = new FormData();
        formData.append('NomeCompleto', NomeCompleto);
        formData.append('NomeUser', NomeUser);
        formData.append('SenhaUser', SenhaUser);
        formData.append('Cargo', Cargo);
        formData.append('img', imagem, tituloFormatado + '.' + extensao);

        fetch('assets/php/addAutor.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                console.log('Dados enviados com sucesso!');
                window.location.href = 'assets/php/addAutor.php';
            } else {
                return response.text().then(text => { throw new Error(text) });
            }
        })
        .catch(error => {
            console.error('Erro ao enviar os dados:', error);
            alert('Ocorreu um erro ao enviar os dados. Por favor, tente novamente.');
        });
    });
}

function formatarTitulo(titulo) {
    titulo = titulo.trim().replace(/\s+/g, '-');
    titulo = titulo.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    return titulo.toLowerCase();
}