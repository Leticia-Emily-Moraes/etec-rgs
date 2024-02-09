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
    Tituto VARCHAR(50) NOT NULL,
    Image BLOB NOT NULL,
    Descricao VARCHAR(255) NOT NULL,
    Autor INT NOT NULL,
    HoraPublicado DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (Autor) REFERENCES usuarios(IdUsuario),
PRIMARY KEY(IdNoticia)
);

INSERT INTO usuarios (Nome, Sobrenome, NomeUser, SenhaUser, Cargo) VALUES ('Rodrigo','Vicente','Root','Admin2024', 'Administrador');

SELECT * FROM usuarios;