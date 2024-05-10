const textConteudo = document.getElementById('Desc');
const inputImg = document.querySelector("#img");
const buttonAddText = document.getElementById('add-Text');
const buttonAddImg = document.getElementById('add-Img');
const buttonLimpar= document.getElementById('Limpar');
const buttonLimpaTudo = document.getElementById('LimparTudo');
const previewNoticia = document.querySelector('.PreviewPost');

const PreImagem = document.querySelector(".ImagemPreview");
PreImagem.innerHTML = "Coloque uma imagem";
let contButtontext = 0;
let contButtonimg = 0;

let texts = [];
let imgs = [];

buttonAddText.addEventListener('click', function(){
    if(contButtontext < 4){
        texts.push(textConteudo.value);
        const p = document.createElement('p');
        p.innerHTML = textConteudo.value;
        previewNoticia.appendChild(p);
        textConteudo.value = '';
        contButtontext++;
    }
    else{
        alert('Limite de textos atingido');
        textConteudo.value = '';
        textConteudo.placeholder = 'Limite de 4 textos atingidos';
        buttonAddText.disabled = true;
    }
});

buttonAddImg.addEventListener('click', function(){
    if(contButtonimg < 4){
        PreImagem.innerHTML = "Coloque uma imagem";
        const leitor = new FileReader();

        leitor.readAsDataURL(inputImg.files[0]);
        leitor.addEventListener("load", function(e) {
            const imagemCarregada = e.target;
            const imagem = document.createElement('img');
            imagem.src = imagemCarregada.result;
            previewNoticia.appendChild(imagem);
        });
        imgs.push(inputImg.value);
        contButtonimg++;
    }
    else{
        alert('Limite de imagens atingido');
        PreImagem.innerHTML = "Limite Atingido";
        inputImg.value = '';
        buttonAddImg.disabled = true;
    }
});

buttonLimpar.addEventListener('click', function() {
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


buttonLimpaTudo.addEventListener('click', function() {
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

inputImg.addEventListener("change", function(e) {
    const arquivo = e.target.files[0];

    if (arquivo) {
        const leitor = new FileReader();

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
        PreImagem.innerHTML = "Coloque a Imagem";
    }
});