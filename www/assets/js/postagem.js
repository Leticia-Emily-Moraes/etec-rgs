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

