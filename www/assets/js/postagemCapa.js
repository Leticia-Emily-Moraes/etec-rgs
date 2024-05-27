// Função para formatar o título
function formatarTitulo(titulo) {
    titulo = titulo.trim().replace(/\s+/g, '-'); // Substituir espaços por hífen
    titulo = titulo.normalize('NFD').replace(/[\u0300-\u036f]/g, ''); // Remover acentos
    return titulo.toLowerCase(); // Converter para minúsculas
}

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

            // Armazenar imagem como base64 no localStorage
            localStorage.setItem('imagemCapa', imagemCarregada.result);
        });

    } else {
        PreImagem.innerHTML = imagemTxt;
    }
});

function confirmacao(titulo, resumo, tema, imagem, callback) {
    let confirmacao = document.querySelector(".confirmacao");
    const pTitulo = document.querySelector('p#TituloConfirm');
    const pResumo = document.querySelector('p#ResumoConfirm');
    const pTema = document.querySelector('p#TemasConfirm');
    const ImgCapa = document.querySelector('img#ImagemConfirm');
    const botaoConfirmar = document.getElementById('buttonConfirmaNoticia');
    const botaoCancela = document.getElementById('buttonCancelaNoticia');

    confirmacao.style.display = "flex";
    pTitulo.textContent = titulo;
    pResumo.textContent = resumo;
    pTema.textContent = tema;
    ImgCapa.src = URL.createObjectURL(imagem);

    botaoConfirmar.addEventListener('click', function() {
        confirmacao.style.display = "none";
        callback(true); // Chama o callback com true quando confirmado
    });

    botaoCancela.addEventListener('click', function() {
        confirmacao.style.display = "none";
        callback(false); // Chama o callback com false quando cancelado
    });
}

const form = document.getElementById('postForm');
form.addEventListener('submit', function(event) {
    // Prevenir o envio padrão do formulário
    event.preventDefault();

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

    // Armazenar dados no localStorage
    localStorage.setItem('titulo', titulo);
    localStorage.setItem('resumo', resumo);
    localStorage.setItem('tema', temas);

    // Exibir popup de confirmação
    confirmacao(titulo, resumo, temas, imagem, function(confirmado) {
        document.body.style.overflow = 'hidden';
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        if (confirmado) {
            const formData = new FormData();
            formData.append('Titulo', titulo);
            formData.append('Resumo', resumo);
            formData.append('Temas', temas);
            formData.append('img', imagem, tituloFormatado + '-capa.' + extensao); // Renomear a imagem
    
            // Submeter o formulário via fetch
            fetch('assets/php/postagemCapa.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = 'postagemConteudo.html';
                    form.reset();
                } else {
                    throw new Error('Erro ao enviar os dados.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Ocorreu um erro ao enviar os dados. Por favor, tente novamente.');
            });
        } else {
            // Se cancelado, não fazer nada
            return;
        }
    });
});
