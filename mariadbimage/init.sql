CREATE DATABASE MyCloudDatabase;
use MyCloudDatabase;
CREATE TABLE users (
    id_user int not null AUTO_INCREMENT primary key,
    email varchar(255) not null unique,
    password varchar(255) not null,
    nume_prenume varchar(255) not null
);
CREATE TABLE files (
    id_file int not null AUTO_INCREMENT primary key,
    genName varchar(255) not null unique,
    fileName varchar(255) not null,
    emailUser varchar(255) not null,
    creationDate datetime not null,
    FOREIGN KEY (emailUser) REFERENCES users (email)
);