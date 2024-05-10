// Função para formatar o título
function formatarTitulo(titulo) {
    titulo = titulo.trim().replace(/\s+/g, '-'); // Substituir espaços por hífens
    titulo = titulo.normalize('NFD').replace(/[\u0300-\u036f]/g, ''); // Remover acentos
    return titulo.toLowerCase(); // Converter para minúsculas
}

// Evento de envio do formulário
document.getElementById('postForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Impedir envio padrão do formulário

    // Obter dados do formulário
    const titulo = document.getElementById('Titulo').value;
    const resumo = document.getElementById('Resumo').value;
    const temas = document.getElementById('Temas').value;
    const imagem = document.getElementById('img').files[0];

    // Validar se todos os campos estão preenchidos
    if (!titulo || !resumo || !temas || !imagem) {
        alert("Por favor, preencha todos os campos.");
        return;
    }

    // Verificar tamanho da imagem (limite de 5MB)
    if (imagem.size > 5 * 1024 * 1024) {
        alert("Desculpe, o arquivo é muito grande (limite de 5MB).");
        return;
    }

    // Obter extensão da imagem
    const extensaoValida = ['jpg', 'jpeg', 'png', 'gif'];
    const extensao = imagem.name.split('.').pop().toLowerCase();
    if (!extensaoValida.includes(extensao)) {
        alert("Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.");
        return;
    }

    // Formatar o título
    const tituloFormatado = formatarTitulo(titulo);

    // Criar um objeto FormData para enviar os dados do formulário
    const formData = new FormData();
    formData.append('Titulo', titulo);
    formData.append('Resumo', resumo);
    formData.append('Temas', temas);
    formData.append('img', imagem, tituloFormatado + '-capa.' + extensao); // Renomear a imagem

    // Enviar dados para o arquivo PHP de processamento
    fetch('../Postagens/postagemCapa.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (response.ok) {
                window.location.href = 'Principal.html'; // Redirecionar após o sucesso
            } else {
                throw new Error('Erro ao enviar os dados.');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Ocorreu um erro ao enviar os dados. Por favor, tente novamente.');
        });
});
const inputImg = document.querySelector("#img");
const PreImagem = document.querySelector(".ImagemPreview");
const imagemTxt = "Coloque a Imagem";
PreImagem.innerHTML = imagemTxt;

inputImg.addEventListener("change", function(e) {
    const Mudanca = e.target;
    const arquivo = Mudanca.files[0];

    if (arquivo) {
        const imagemTxt = "imagem colocada";
        const leitor = new FileReader();
        console.log(leitor);

        leitor.readAsDataURL(arquivo);
        leitor.addEventListener("load", function(e) {
            const imagemCarregada = e.target;
            const imagem = document.createElement('img');
            imagem.src = imagemCarregada.result;
            imagem.classList.add('ImgViewer');

            PreImagem.innerHTML = " ";
            PreImagem.appendChild(imagem);
        });

    } else {
        PreImagem.innerHTML = imagemTxt;
    }
})
