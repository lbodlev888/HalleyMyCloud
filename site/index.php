<?php
    require "connect.php";

    if(!isset($_SESSION['user'])) header("Location: login.php");

    $user = $_SESSION['user'];
    $sql = "SELECT nume_prenume AS nume FROM users WHERE email='$user'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $name = explode(' ', $row['nume'])[1];

    //---- get files
    $sql = "SELECT ROW_NUMBER() OVER(ORDER BY fileName) as id, fileName as file, genName as gen, creationDate as crDate from files where emailUser='$user'";
    $result = $conn->query($sql);
    $files = $result->fetch_all(MYSQLI_ASSOC);

    //---- get file size function
    function getSize($file_path)
    {
        $units = array("B", "KB", "MB", "GB");
        $sbytes = filesize($file_path);
        $count = 0;
        while($sbytes > 1024)
        {
            $sbytes = round($sbytes/1024, 2);
            $count++;
        }
        return strval($sbytes)." $units[$count]";
    }
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyCloud - Home</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style>
        .container { margin-left: 5px; }
        .down-button {  border: none; }
        .bx { font-size: 25px; }
        tr { text-align: center; }
    </style>
</head>
<body>
    <div class="container p-3">
        <div class="d-flex justify-content-between align-items-center">
        <h2>Bine ai venit <?= $name ?></h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <div class="d-flex align-items-center">
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputGroupFile02" name="file">
                            <label class="custom-file-label" for="inputGroupFile02">Alege fișierul</label>
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text">Încarcă</button>
                        </div>
                    </div>
                </div>
            </form>
            <button onClick="location.href = 'logout.php';" type="submit" class="btn btn-primary">Deconectează-te</button>
        </div>
        <?php
            if(isset($_GET['uploaded']) && $_GET['uploaded'] == 'failed') { ?>
            <p style="color: red;margin-top: 10px;">Încărcarea fișierului nu a reușit</p>
        <?php } ?>
        <?php
            if(isset($_GET['uploaded']) && $_GET['uploaded'] == 'big') { ?>
            <p style="color: red;margin-top: 10px;">Fișierul este prea mare</p>
        <?php } ?>
        <?php
            if(isset($_GET['uploaded']) && $_GET['uploaded'] == 'ok') { ?>
            <p style="color: green;margin-top: 10px; font-size: 20px">Încărcat cu succes</p>
        <?php } ?>
        <?php
            if(isset($_GET['status']) && $_GET['status'] == 'invalidrequest') { ?>
            <p style="color: red;margin-top: 10px; font-size: 20px">Cerere invalidă</p>
        <?php } ?>
        <?php
            if(isset($_GET['status']) && $_GET['status'] == 'deleteSuccess') { ?>
            <p style="color: green;margin-top: 10px; font-size: 20px">Fișierul a fost șters</p>
        <?php } ?>
        <?php
            if(isset($_GET['status']) && $_GET['status'] == 'neimplementat') { ?>
            <p style="color: red;margin-top: 10px; font-size: 20px">Funcția dată încă nu a fost implementată</p>
        <?php } ?>
    </div>
    <br>
    <div class="files p-3">
        <h2>Fisierele tale</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="ord" scope="col">Nr. ord.</th>
                    <th scope="col">Nume fișier</th>
                    <th scope="col">Mărimea fișierului</th>
                    <th scope="col">Data creării</th>
                    <th scope="col">Opțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($files as $file) { ?>
                    <tr>
                        <th class="ord" scope="row"><?= $file['id'] ?></th>
                        <td><?= $file['file'] ?></td>
                        <td><?= getSize("/var/www/uploads/".$file['gen']) ?></td>
                        <td><?= $file['crDate'] ?></td>
                        <td>
                            <a class="btn btn-primary text-white" href="option.php?download&file=<?= $file['gen'] ?>"><i class='bx bx-cloud-download'></i> Download</a>
                            <a class="btn btn-info text-white justify-content-between" href="option.php?ren&file=<?= $file['gen'] ?>"><i class='bx bx-rename'></i> Redenumește</a>
                            <a class="btn btn-danger text-white" href="option.php?del&file=<?= $file['gen'] ?>"><i class='bx bx-folder-minus'></i> Șterge</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>