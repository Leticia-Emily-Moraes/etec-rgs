const nomesMaterias = document.querySelectorAll('.materias > h2');
const containersMaterias = document.querySelectorAll('.materias > aside');

nomesMaterias.forEach((nomeMateria, index) => {
    nomeMateria.addEventListener('click', () => {
        const containerMaterias = containersMaterias[index];
        if(!containerMaterias.classList.contains('cardvisivel')) {
            containerMaterias.classList.add('cardvisivel');
        } else {
            containerMaterias.classList.remove('cardvisivel');
        }
    });
});