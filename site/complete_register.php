<title>MyCloud</title>
<?php
    if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['pass'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>window.location.href='register.php?error=invalidemail'</script>";
            die();
        }
        if(strlen($pass) < 8) {
            echo "<script>window.location.href='register.php?error=smallpassword'</script>";
            die();
        }
        require "connect.php";
        $sql = "SELECT count(email) as cnt FROM users WHERE email='$email'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if($row['cnt'] >= 1) echo "<script>window.location.href='register.php?error=ununique'</script>";
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (nume_prenume, email, password) VALUES ('$name', '$email', '$pass')";
        //echo $sql."\n";
        $conn->query($sql);
        echo "<script>window.location.href='register.php?ok=true'</script>";
    }
    else echo "<script>window.location.href='register.php?error=empty'</script>";
?>