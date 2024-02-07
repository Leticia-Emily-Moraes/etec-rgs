const container = document.querySelector('.container'),
qrInput = container.querySelector('.rm '),
generateBtn = container.querySelector('.form button'),
qrImg = container.querySelector('.qr-code .qrimg');

generateBtn.addEventListener('click', () => {
    let qrValue = qrInput.value;
    if(!qrValue){
        alert('Insira algum documento para criar o QRCODE')
        return;
    }

    generateBtn.innerText = "Cadastrando...";
    qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=170x170&data=${qrValue}`;
    qrImg.addEventListener('load', () => {
        generateBtn.innerText = "Cadastrar";
        container.classList.add('active');
    });
});


qrInput.addEventListener('keyup', () => {
    if(!qrInput.value){
        container.classList.remove('active');
    };
});
