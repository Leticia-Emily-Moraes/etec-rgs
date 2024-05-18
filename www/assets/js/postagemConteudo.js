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

    if (!textConteudo || !inputImg || !buttonAddText || !buttonAddImg || !buttonLimpar || !buttonLimpaTudo || !buttonPublicarNoticia || !previewNoticia || !PreImagem) {
        console.error('Um ou mais elementos não foram encontrados no DOM');
        return;
    }

    PreImagem.innerHTML = "Coloque uma imagem";
    let contButtontext = 0;
    let contButtonimg = 0;

    let texts = [];
    let imgs = [];

    buttonAddText.addEventListener('click', function () {
        if (contButtontext < 4) {
            texts.push(textConteudo.value);
            console.log(texts);
            const p = document.createElement('p');
            p.innerHTML = textConteudo.value;
            previewNoticia.appendChild(p);
            textConteudo.value = '';
            contButtontext++;
        } else {
            alert('Limite de textos atingido');
            textConteudo.value = '';
            textConteudo.placeholder = 'Limite de 4 textos atingidos';
            buttonAddText.disabled = true;
        }
    });

    buttonAddImg.addEventListener('click', function() {
        if (contButtonimg < 4) {
            PreImagem.innerHTML = "Coloque uma imagem";
            const leitor = new FileReader();
            const file = inputImg.files[0]; // Obtendo o arquivo de imagem real
            
            leitor.readAsDataURL(file);
            leitor.addEventListener("load", function(e) {
                const imagemCarregada = e.target;
                const imagem = document.createElement('img');
                imagem.src = imagemCarregada.result;
                previewNoticia.appendChild(imagem);
            });
    
            imgs.push(file); // Armazene o arquivo de imagem real
            console.log(imgs);
            contButtonimg++;
        } else {
            alert('Limite de imagens atingido');
            PreImagem.innerHTML = "Limite Atingido";
            inputImg.value = '';
            buttonAddImg.disabled = true;
        }
    });

    buttonLimpar.addEventListener('click', function () {
        if (previewNoticia.childElementCount > 0) {
            if (previewNoticia.lastChild.tagName === 'IMG') {
                imgs.pop();
                contButtonimg--;
                buttonAddImg.disabled = false;
                PreImagem.innerHTML = "Coloque uma imagem";
            } else {
                texts.pop();
                contButtontext--;
                buttonAddText.disabled = false;
                textConteudo.placeholder = 'Texto da sua Noticia - máximo 200 caracteres';
            }
            previewNoticia.removeChild(previewNoticia.lastChild);
        } else {
            alert('Nada para limpar.');
        }
    });

    buttonLimpaTudo.addEventListener('click', function () {
        while (previewNoticia.firstChild) {
            previewNoticia.removeChild(previewNoticia.firstChild);
        }
        texts = [];
        imgs = [];
        contButtontext = 0;
        contButtonimg = 0;
        buttonAddText.disabled = false;
        buttonAddImg.disabled = false;
        PreImagem.innerHTML = "Coloque uma imagem";
        textConteudo.placeholder = 'Texto da sua Noticia - máximo 200 caracteres';
    });

    buttonPublicarNoticia.addEventListener('click', function () {
        if (texts.length < 1 || imgs.length < 1) {
            alert("Insira pelo menos um texto e uma imagem para publicar a notícia");
        } else {
            enviarDadosParaPHP();
        }
    });

    function enviarDadosParaPHP() {
        const dados = { texts: texts };
        const formData = new FormData();
    
        // Adicionando textos ao formData
        formData.append('dados', JSON.stringify(dados));
    
        // Adicionando imagens ao formData
        imgs.forEach(function (img, index) {
            formData.append('imgConteudo[]', img); // Note o uso de imgConteudo[] para enviar um array de arquivos
        });
    
        fetch('../Postagens/postagemConteudo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.text(); // retornar o conteúdo da resposta
            } else {
                throw new Error('Erro ao enviar os dados.');
            }
        })
        .then(data => {
            if (data === 'success') {
                alert('Notícia cadastrada com sucesso!');
                window.location.href = '../Postagens/postagemConteudo.php';
            } else {
                alert('Erro ao cadastrar a notícia.');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Ocorreu um erro ao enviar os dados. Por favor, tente novamente.');
        });
    }
    


    inputImg.addEventListener("change", function (e) {
        const arquivo = e.target.files[0];
        if (arquivo) {
            const leitor = new FileReader();

            leitor.readAsDataURL(arquivo);
            leitor.addEventListener("load", function (e) {
                const imagemCarregada = e.target;
                const imagem = document.createElement('img');
                imagem.src = imagemCarregada.result;
                imagem.classList.add('ImgViewer');

                PreImagem.innerHTML = " ";
                PreImagem.appendChild(imagem);
            });
        } else {
            PreImagem.innerHTML = "Coloque a Imagem";
        }
    });
});
