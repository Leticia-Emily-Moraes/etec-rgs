document.addEventListener('DOMContentLoaded', function () {
    const textConteudo = document.getElementById('Desc');
    const inputImg = document.querySelector("#imgConteudo");
    const buttonAddText = document.getElementById('add-Text');
    const buttonAddImg = document.getElementById('add-Img');
    const buttonLimpar = document.getElementById('Limpar');
    const buttonLimpaTudo = document.getElementById('LimparTudo');
    const buttonPublicarNoticia = document.getElementById('PublicarNoticia');
    const previewNoticia = document.querySelector('.PreviewPost');
    const PreImagem = document.querySelector(".ImagemPreview");

    let contButtontext = 0;
    let contButtonimg = 0;
    let texts = [];
    let imgs = [];

    PreImagem.textContent = "Coloque uma imagem";

    buttonAddText.addEventListener('click', adicionarTexto);
    buttonAddImg.addEventListener('click', adicionarImagem);
    buttonLimpar.addEventListener('click', limparUltimo);
    buttonLimpaTudo.addEventListener('click', limparTudo);
    buttonPublicarNoticia.addEventListener('click', publicarNoticia);
    inputImg.addEventListener("change", previewImagem);

    function adicionarTexto() {
        if (contButtontext < 4) {
            const texto = textConteudo.value.trim();
            if (texto) {
                texts.push(texto);
                const p = document.createElement('p');
                p.textContent = texto;
                previewNoticia.appendChild(p);
                textConteudo.value = '';
                contButtontext++;
            }
        } else {
            alert('Limite de textos atingido');
            textConteudo.value = '';
            textConteudo.placeholder = 'Limite de 4 textos atingidos';
            buttonAddText.disabled = true;
        }
    }

    function adicionarImagem() {
        if (contButtonimg < 4) {
            const arquivo = inputImg.files[0];
            if (arquivo) {
                const leitor = new FileReader();
                leitor.readAsDataURL(arquivo);
                leitor.addEventListener("load", function (e) {
                    const imagemCarregada = e.target.result;
                    const imagem = document.createElement('img');
                    imagem.src = imagemCarregada;
                    previewNoticia.appendChild(imagem);
                });
                imgs.push(arquivo);
                contButtonimg++;
            }
        } else {
            alert('Limite de imagens atingido');
            PreImagem.textContent = "Limite Atingido";
            inputImg.value = '';
            buttonAddImg.disabled = true;
        }
    }

    function limparUltimo() {
        if (previewNoticia.childElementCount > 0) {
            const ultimoElemento = previewNoticia.lastChild;
            if (ultimoElemento.tagName === 'IMG') {
                imgs.pop();
                contButtonimg--;
                buttonAddImg.disabled = false;
                PreImagem.textContent = "Coloque uma imagem";
            } else {
                texts.pop();
                contButtontext--;
                buttonAddText.disabled = false;
                textConteudo.placeholder = 'Texto da sua Notícia - máximo 200 caracteres';
            }
            previewNoticia.removeChild(ultimoElemento);
        } else {
            alert('Nada para limpar.');
        }
    }

    function limparTudo() {
        while (previewNoticia.firstChild) {
            previewNoticia.removeChild(previewNoticia.firstChild);
        }
        texts = [];
        imgs = [];
        contButtontext = 0;
        contButtonimg = 0;
        buttonAddText.disabled = false;
        buttonAddImg.disabled = false;
        PreImagem.textContent = "Coloque uma imagem";
        textConteudo.placeholder = 'Texto da sua Notícia - máximo 200 caracteres';
    }

    function publicarNoticia() {
        if (texts.length < 1 || imgs.length < 1) {
            alert("Insira pelo menos um texto e uma imagem para publicar a notícia");
        } else {
            const titulo = localStorage.getItem('titulo');
            const resumo = localStorage.getItem('resumo');
            const tema = localStorage.getItem('tema');
            const imagemCapaBase64 = localStorage.getItem('imagemCapa');

            // Convert base64 string back to Blob
            const byteCharacters = atob(imagemCapaBase64.split(',')[1]);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            const blob = new Blob([byteArray], { type: 'image/jpeg' });

            const imagemCapa = new File([blob], "capa.jpg", { type: blob.type });
            confirmacao(titulo, resumo, tema, imagemCapa,
                function (confirmado) {
                if (confirmado) {
                    enviarDadosParaPHP();
                }
            });
        }
    }

    function enviarDadosParaPHP() {
        const dados = { texts: texts };
        const formData = new FormData();
        formData.append('dados', JSON.stringify(dados));
        imgs.forEach(function (img) {
            formData.append('imgConteudo[]', img);
        });

        fetch('../Postagens/postagemConteudo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                console.log(response.text());
                alert('Notícia cadastrada com sucesso!');
                window.location.href = '../Postagens/postagemConteudo.php';
            } else {
                throw new Error('Erro ao enviar os dados.');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Ocorreu um erro ao enviar os dados. Por favor, tente novamente.');
        });
    }

    function previewImagem(e) {
        const arquivo = e.target.files[0];
        if (arquivo) {
            const leitor = new FileReader();
            leitor.readAsDataURL(arquivo);
            leitor.addEventListener("load", function (e) {
                const imagemCarregada = e.target.result;
                const imagem = document.createElement('img');
                imagem.src = imagemCarregada;
                imagem.classList.add('ImgViewer');
                PreImagem.innerHTML = " ";
                PreImagem.appendChild(imagem);
            });
        } else {
            PreImagem.textContent = "Coloque a Imagem";
        }
    }

    function confirmacao(titulo, resumo, tema, imagemCapa, callback) {
        const confirmacao = document.querySelector(".confirmacao");
        const pTituloCapa = document.querySelector('p#TituloCapaConfirm');
        const pResumoCapa = document.querySelector('p#ResumoCapaConfirm');
        const pTemaCapa = document.querySelector('p#TemasCapaConfirm');
        const ImgCapa = document.querySelector('img#ImagemCapaConfirm');
        const conteudoContainer = document.querySelector('.ConfirmacaoConteudo');
        const botaoConfirmar = document.getElementById('buttonConfirmaNoticia');
        const botaoCancela = document.getElementById('buttonCancelaNoticia');
    
        // Limpar conteúdo anterior
        conteudoContainer.innerHTML = '<h2>Conteudo</h2>';
    
        // Preencher dados de confirmação
        confirmacao.style.display = "flex";
        pTituloCapa.textContent = titulo;
        pResumoCapa.textContent = resumo;
        pTemaCapa.textContent = tema;
        ImgCapa.src = URL.createObjectURL(imagemCapa);
    
        // Adicionar textos ao container de confirmação
        texts.forEach((texto, index) => {
            const div = document.createElement('div');
            const label = document.createElement('label');
            label.textContent = `Texto ${index + 1}:`;
            const p = document.createElement('p');
            p.textContent = texto;
            p.classList.add('TextConteudoConfirm');
            div.appendChild(label);
            div.appendChild(p);
            conteudoContainer.appendChild(div);
        });
    
        // Adicionar imagens ao container de confirmação
        imgs.forEach((imagem, index) => {
            const div = document.createElement('div');
            const label = document.createElement('label');
            label.textContent = `Imagem ${index + 1}:`;
            const img = document.createElement('img');
            img.classList.add('ImagemConteudoConfirm');
            img.src = URL.createObjectURL(imagem);
            div.appendChild(label);
            div.appendChild(img);
            conteudoContainer.appendChild(div);
        });
    
        botaoConfirmar.onclick = () => {
            confirmacao.style.display = "none";
            callback(true);
        };
    
        botaoCancela.onclick = () => {
            confirmacao.style.display = "none";
            callback(false);
        };
    }    
});
