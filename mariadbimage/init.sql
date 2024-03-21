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
    downTimes int DEFAULT 0,
    FOREIGN KEY (emailUser) REFERENCES users (email)
);
DELIMITER //
CREATE PROCEDURE UpdateDownTimes ( IN fileid varchar(255) )
BEGIN
    DECLARE exit handler FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Operațiunea nu a reușit' as stat;
    END;
    START TRANSACTION;
    UPDATE files SET downTimes = downTimes + 1 WHERE genName=fileid;
    COMMIT;
    SELECT (SELECT 'Operațiunea a reușit') AS stat, (SELECT fileName FROM files WHERE genName=fileid) AS fileName;
END //
DELIMITER