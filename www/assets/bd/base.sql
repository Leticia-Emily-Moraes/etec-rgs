DROP DATABASE IF EXISTS SiteEtec;

CREATE DATABASE SiteEtec;

USE SiteEtec;

CREATE TABLE usuarios (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    NomeCompleto VARCHAR(100) NOT NULL,
    NomeUser VARCHAR(50) NOT NULL,
    SenhaUser VARCHAR(100) NOT NULL, 
    Cargo VARCHAR(50) NOT NULL,
    ImagemAutor VARCHAR(255) NOT NULL,
    UNIQUE (NomeUser), 
    INDEX (NomeUser)
);

CREATE TABLE noticias (
	IdNoticia INT AUTO_INCREMENT NOT NULL,
    Titulo VARCHAR(50) NOT NULL,
    Resumo VARCHAR(200) NOT NULL,
    Categoria VARCHAR(50) NOT NULL,
    ImagemCapa VARCHAR(255) NOT NULL,
    Autor INT NOT NULL,
    HoraPublicado DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (Autor) REFERENCES usuarios(IdUsuario),
    PRIMARY KEY(IdNoticia)
);

CREATE TABLE noticiasConteudo (
	IdConteudo INT AUTO_INCREMENT NOT NULL,
    IdNoticiaConteudo INT NOT NULL,
    Text1 VARCHAR(300) NOT NULL,
    Imagem1 VARCHAR(255) NOT NULL,
	Text2 VARCHAR(300),
    Imagem2 VARCHAR(255),
    Text3 VARCHAR(300),
    Imagem3 VARCHAR(255),
    Text4 VARCHAR(300),
    Imagem4 VARCHAR(255),
    FOREIGN KEY (IdNoticiaConteudo) REFERENCES noticias(IdNoticia),
    PRIMARY KEY(IdConteudo)
);

CREATE TABLE galeriaDeImagens(
	IdGaleria INT AUTO_INCREMENT NOT NULL,
    IdNoticia INT NOT NULL,
    Imagem1 VARCHAR(255) NOT NULL,
    Imagem2 VARCHAR(255) NOT NULL,
    Imagem3 VARCHAR(255) NOT NULL,
    Imagem4 VARCHAR(255) NOT NULL,
    Imagem5 VARCHAR(255) NOT NULL,
    Imagem6 VARCHAR(255) NOT NULL,
    Imagem7 VARCHAR(255) NOT NULL,
    Imagem8 VARCHAR(255) NOT NULL,
    Imagem9 VARCHAR(255) NOT NULL,
    Imagem10 VARCHAR(255) NOT NULL,
    Imagem11 VARCHAR(255) NOT NULL,
    Imagem12 VARCHAR(255) NOT NULL,
    Imagem13 VARCHAR(255) NOT NULL,
    Imagem14 VARCHAR(255) NOT NULL,
    Imagem15 VARCHAR(255) NOT NULL,
    FOREIGN KEY (IdNoticia) REFERENCES noticias(IdNoticia),
    PRIMARY KEY(IdGaleria)
);

SELECT * FROM usuarios;

SELECT * FROM noticias;

SELECT * FROM noticiasConteudo;
