DROP DATABASE IF EXISTS SiteEtec;

CREATE DATABASE SiteEtec;

USE SiteEtec;

CREATE TABLE usuarios (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    NomeCompleto VARCHAR(100) NOT NULL,
    NomeUser VARCHAR(50) NOT NULL,
    SenhaUser VARCHAR(100) NOT NULL, 
    Cargo VARCHAR(50) NOT NULL,
    UNIQUE (NomeUser), 
    INDEX (NomeUser)
);

CREATE TABLE noticias (
	IdNoticia INT AUTO_INCREMENT NOT NULL,
    Titulo VARCHAR(50) NOT NULL,
    LoginParaPublicacoes
    Resumo VARCHAR(100) NOT NULL,
    Categoria VARCHAR(50) NOT NULL,
    ImagemCapa VARCHAR(255) NOT NULL,
    Autor INT NOT NULL,
    HoraPublicado DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (Autor) REFERENCES usuarios(IdUsuario),
    PRIMARY KEY(IdNoticia)
);

CREATE TABLE noticiasConteudo (
	IdConteudo INT AUTO_INCREMENT NOT NULL,
    FkNoticia INT NOT NULL,
    LoginParaPublicacoes
    Text1 VARCHAR(100) NOT NULL,
    Imagem1 VARCHAR(255) NOT NULL,
	  Text2 VARCHAR(100),
    Imagem2 VARCHAR(255),
    Text3 VARCHAR(100),
    Imagem3 VARCHAR(255),
    Text4 VARCHAR(100),
    Imagem4 VARCHAR(255),
    FOREIGN KEY (FkNoticia) REFERENCES noticias(IdNoticia),
    PRIMARY KEY(IdConteudo)
);

INSERT INTO usuarios (NomeCompleto, NomeUser, SenhaUser, Cargo) VALUES ('Rodrigo Vicente','Root', 'Admin2024', 'Administrador');

SELECT * FROM usuarios;

SELECT * FROM noticias;