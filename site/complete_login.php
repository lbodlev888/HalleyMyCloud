<?php
    require "connect.php";

    if(isset($_POST['email']) && isset($_POST['pass']) && !empty($_POST['email']) && !empty($_POST['pass'])) {
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $sql = "SELECT password FROM users WHERE email='".$conn->real_escape_string($email)."'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if(!isset($row['password'])) header("Location: login.php?error=wrong");
        if(password_verify($pass, $row['password'])) {
            $_SESSION['user'] = $email;
            header("Location: index.php");
        }
        else header("Location: login.php?error=wrong");
    }
    else header("Location: login.php?error=empty");
?>