<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyCloud - Registering</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
  <div class="form-log p-3">
    <form action="complete_register.php" method="POST">
        <div class="form-group">
          <label for="exampleInputEmail1">Nume Prenume</label>
          <input type="text" class="form-control" name="name" id="exampleInputEmail1" placeholder="Ex: Bodlev Laurențiu">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Adresa de email</label>
          <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ex: example@mail.com">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Parola</label>
          <input type="password" class="form-control" name="pass" id="exampleInputPassword1" placeholder="Folosește o parolă complicată">
        </div>
        <div class="form-group">
          <div class="d-flex justify-content-between align-items-end">
            <button type="submit" class="btn btn-primary">Creeaza contul</button>
            <a href="index.php" class="btn btn-info">Login</a>
          </div>
        </div>
      <?php
      if(isset($_GET['error']) && $_GET['error'] == 'empty') { ?>
      <p style="color: red;margin-top: 10px;">Nu au fost completate toate spatiile</p>
      <?php } ?>
      <?php
      if(isset($_GET['error']) && $_GET['error'] == 'invalidemail') { ?>
      <p style="color: red;margin-top: 10px;">Adresa de email invalida</p>
      <?php } ?>
      <?php
      if(isset($_GET['error']) && $_GET['error'] == 'smallpassword') { ?>
      <p style="color: red;margin-top: 10px;">Parola este prea scurta</p>
      <?php } ?>
      <?php
      if(isset($_GET['error']) && $_GET['error'] == 'ununique') { ?>
      <p style="color: red;margin-top: 10px;">Emailul este deja folosit</p>
      <?php } ?>
      <?php
      if(isset($_GET['ok']) && $_GET['ok'] == 'true') { ?>
      <p style="color: green;margin-top: 10px;">Cont înregistrat cu succes</p>
      <a href="login.php"><p>Mergi la login</p></a>
      <?php } ?>
    </form>
  </div>
</body>
</html>