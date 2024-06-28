const submit = document.getElementById('submit');

submit.addEventListener("click", function () {
    form.reset();
});

const inputSenha = document.querySelector('input[type="password"]');
const imgVisivel = document.querySelector('img');
imgVisivel.addEventListener('click', () => {
    if (inputSenha.type === 'password') {
        inputSenha.type = 'text';
        imgVisivel.src = 'assets/img/icons/Invisivel.svg';
    } else {
        inputSenha.type = 'password';
        imgVisivel.src = 'assets/img/icons/Visivel.svg';
    }
});