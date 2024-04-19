DROP DATABASE IF EXISTS SiteEtec;

CREATE DATABASE SiteEtec;

USE SiteEtec;

CREATE TABLE usuarios (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    NomeCompleto VARCHAR(100) NOT NULL,
    NomeUser VARCHAR(50) NOT NULL,
    SenhaUser VARCHAR(255) NOT NULL,
    Cargo VARCHAR(50) NOT NULL
);

CREATE TABLE noticias (
	IdNoticia INT AUTO_INCREMENT NOT NULL,
    Titulo VARCHAR(50) NOT NULL,
    ImagemCapa BLOB NOT NULL,
    Descricao VARCHAR(100) NOT NULL,
    Autor INT NOT NULL,
    Categoria VARCHAR(50) NOT NULL,
    HoraPublicado DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (Autor) REFERENCES usuarios(IdUsuario),
PRIMARY KEY(IdNoticia)
);

CREATE TABLE noticiasConteudo (
	IdConteudo INT AUTO_INCREMENT NOT NULL,
    FkNoticia INT NOT NULL,
    Text1 VARCHAR(50) NOT NULL,
    Imagem1 BLOB NOT NULL,
	Text2 VARCHAR(50),
    Imagem2 BLOB,
    Text3 VARCHAR(50),
    Imagem3 BLOB,
    Text4 VARCHAR(50),
    Imagem4 BLOB,
    FOREIGN KEY (FkNoticia) REFERENCES noticias(IdNoticia),
PRIMARY KEY(IdConteudo)
);

INSERT INTO usuarios (NomeCompleto, NomeUser, SenhaUser, Cargo) VALUES ('Rodrigo Vicente','Root','Admin2024', 'Administrador');

SELECT * FROM usuarios;