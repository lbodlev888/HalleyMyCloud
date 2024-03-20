<?php
    require "connect.php";

    if(!isset($_FILES['file']) || empty($_FILES['file'])) header('Location: index.php');
    if(!isset($_SESSION['user']) || empty($_SESSION['user'])) header('Location: login.php');
    if($_FILES['file']['size'] > 2000000000) header('Location: index.php?uploaded=big');

    $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    define('file_length', 20);
    $genName = "";
    for($i = 0; $i < file_length; $i++) $genName .= $alphanum[rand(0, 61)];
    $filename = $_FILES['file']['name'];
    
    $email = $_SESSION['user'];
    $now = strval(date('Y-m-d H:i:s'));
    $full_gen_name = $genName.".".strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $sql = "INSERT INTO files (genName, fileName, emailUser, creationDate) VALUES ('$full_gen_name', '$filename', '$email', '$now')";
    $conn->query($sql);

    $target_path = "/var/www/uploads/".$full_gen_name;
    if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path))
        header('Location: index.php?uploaded=ok');
    else header('Location: index.php?uploaded=failed');
?>