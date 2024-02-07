create database EtecRGS;
use EtecRGS;
create table noticias(
id int NOT NULL AUTO_INCREMENT,
titulo varchar(100),
conteudo varchar(2000),
imagem varchar(100),
data varchar(100),
PRIMARY KEY (id)
);

INSERT INTO `noticias` (`id`, `titulo`, `conteudo`, `imagem`, `data`) VALUES
(1, 
'Vestibulinho das Etecs tem inscrições prorrogadas veja como participar do processo', 
'O período de inscrição para o Vestibulinho das Etecs, que terminou na última sexta-feira (18), foi prorrogado. As inscrições seguem abertas até a próxima quarta-feira (23).\r\n\r\nNo vestibulinho para o primeiro semestre de 2023, são oferecidas mais de 88 mil vagas para ensino médio integrado ao técnico, cursos técnicos, especializações técnicas e para vagas remanescentes de segundo módulo.\r\n\r\nA inscrição deve ser feita exclusivamente pela internet, no site do Vestibulinho. O valor da taxa é de R$ 33, e a prova será aplicada no dia 18 de dezembro, de forma presencial.\r\n\r\nAs vagas são destinadas a turmas presenciais, semipresenciais e para cursos online das Etecs e classes descentralizadas, unidades que funcionam com um ou mais cursos técnicos, sob a administração de uma Etec, por meio de parcerias com as prefeituras.\r\n\r\nInformações sobre os pré-requisitos das vagas e orientações para realizar a inscrição podem ser acessadas no site do Vestibulinho.\r\n\r\nNo centro-oeste paulista, há Etecs em cidades como Assis, Bauru, Botucatu, Garça, Jaú, Lins, Marília, Ourinhos e Pompéia (SP). São cerca de 46 unidades espalhadas pela região.', 
'../../assets/img/noticias/fatec.webp',
'10/08/2020');

INSERT INTO `noticias` (`id`, `titulo`, `conteudo`, `imagem`, `data`) VALUES
(2, 
'Evento Empreendedorismo Periférico', 
'Empreendedorismo Periférico no qual tem o intuito de repassar informações para a sociedade sobre o tema,surgiu por meio de uma seleção de temas (feita pelo segundo modulo de administração da ETEC de Rio Grande da Serra), observando-se que é um tema bastante praticado pela comunidade resolveu-se comentar maneiras nos quais ajudariam a sociedade a compreender como funciona este tipo de empreendedorismo (a origem, os desafios e as dificuldades). No evento da Fecontec esse projeto poderá ser mais conhecido não só pelo alunos mas também por visitantes que se interessarem pelo evento', 
'../../assets/img/noticias/perifa.jpeg',
'06/12/2022');

INSERT INTO `noticias` (`id`, `titulo`, `conteudo`, `imagem`, `data`) VALUES
(4, 
'Visita para conhecer a nossa Etec de Rio grande da serra', 
'Ol� somos estudantes do 2� de Administra��o da Etec de Rio Grande da Serra e estamos divulgando o � Vestibulinho de Julho/2023�
Temos como objetivo alcan�ar o maior n�mero de cidad�os que queiram transformar o seu futuro, atrav�s dos cursos da nossa unidade. 
Estamos te convidando para fazer uma visita a nossa Etec .  Assim, voc� poder� ter a oportunidade de conhecer a estrutura da escola e assim ver o quanto ela � importante para todos. 
Sua presen�a � de imensa import�ncia para nossa cidade.
Estamos de portas abertas, aguardamos voc�!
N�o esque�a o dia da visita ser� 27/03/23 �s 19:30.', 
'../../assets/img/noticias/conviteespecial.png',
'27/03/23');
