<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyCloud</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body class="login-background">
  <div class="form-log p-3">
  <form method="POST" action="complete_login.php">
      <div class="form-group">
        <label for="exampleInputEmail1">Adresa de email</label>
          <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Ex: example@mail.com">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Parola</label>
        <input type="password" class="form-control" name="pass" id="exampleInputPassword1" placeholder="Parola utilizată la înregistrare">
      </div>
      <div class="form-group">
        <div class="d-flex justify-content-between align-items-end">
          <button type="submit" class="btn btn-primary">Conectează</button>
          <a href="register.php" class="btn btn-info">Nu ai cont?</a>
        </div>
      </div>
      <?php
      if(isset($_GET['error']) && $_GET['error'] == 'empty') { ?>
      <p style="color: red;margin-top: 10px;">Nu au fost completate toate spatiile</p>
      <?php } ?>
      <?php
      if(isset($_GET['error']) && $_GET['error'] == 'wrong') { ?>
      <p style="color: red;margin-top: 10px;">Cont inexistent sau parola incorecta</p>
      <?php } ?>
    </form>
    </div>
</body>
</html>