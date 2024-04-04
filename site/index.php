<?php
    require "connect.php";

    if(!isset($_SESSION['user'])) header("Location: login.php");

    $user = $_SESSION['user'];
    $sql = "SELECT nume_prenume AS nume FROM users WHERE email='$user'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $name = explode(' ', $row['nume'])[1];
    if(empty($name)) $name = $row['nume'];

    //---- get files
    $sql = "SELECT ROW_NUMBER() OVER(ORDER BY fileName) as id, fileName as file, genName as gen, creationDate as crDate from files where emailUser='$user'";
    $result = $conn->query($sql);
    $files = $result->fetch_all(MYSQLI_ASSOC);

    for($i = 0; $i < count($files); $i++)
        $files[$i]['crDate'] = date('H:i:s d.m.Y', strtotime($files[$i]['crDate']));

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
    <script>
        let fileId = "";
        function genAction()
        {
            let val = document.getElementById('fileName').value;
            // if(val == "") {
            //     alert('Numele nu poate fi gol');
            //     return;
            // }
            let request = 'option.php?ren&file=' + fileId + '&newFileName=' + val;
            document.getElementById('modalRenameForm').action = request;
        }
        function setData(fileid, fileName)
        {
            //document.getElementById('fileName').value = '';
            document.getElementById('fileName').defaultValue = fileName;
            fileId = fileid;
            document.getElementById('labelCaptionRename').innerHTML = "Nume nou pentru " + fileName;
        }
        function genHref(fileid)
        {
            document.getElementById('dela').href = "option.php?del&file=" + fileid;
        }
    </script>
    <style>
        .container { margin-left: 5px; }
        .down-button {  border: none; }
        .bx { font-size: 25px; }
        tr { text-align: center; }
        p { margin-top: 10px; font-size: 35px; }
        .error { color: red; }
        .ok { color: green; }
        .welcome { font-size: 50px; }
    </style>
</head>
<body>
    <div class="container p-3">
        <div class="d-flex justify-content-between align-items-center">
        <h2 class="welcome">Bine ai venit <?= $name ?></h2>
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
            <p class="error">Încărcarea fișierului nu a reușit</p>
        <?php } ?>
        <?php
            if(isset($_GET['uploaded']) && $_GET['uploaded'] == 'big') { ?>
            <p class="error">Fișierul este prea mare</p>
        <?php } ?>
        <?php
            if(isset($_GET['uploaded']) && $_GET['uploaded'] == 'ok') { ?>
            <p class="ok">Încărcat cu succes</p>
        <?php } ?>
        <?php
            if(isset($_GET['status']) && $_GET['status'] == 'invalidrequest') { ?>
            <p class="error">Cerere invalidă</p>
        <?php } ?>
        <?php
            if(isset($_GET['status']) && $_GET['status'] == 'error') { ?>
            <p class="error">Ceva nu a mers</p>
        <?php } ?>
        <?php
            if(isset($_GET['status']) && $_GET['status'] == 'deleteSuccess') { ?>
            <p class="ok">Fișierul a fost șters</p>
        <?php } ?>
        <?php
            if(isset($_GET['status']) && $_GET['status'] == 'renameSuccess') { ?>
            <p class="ok">Fișierul a fost redenumit</p>
        <?php } ?>
    </div>
    <br>
    <div class="files p-3">
        <h2>Fișierele tale</h2>
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
                            <button type="button" class="btn btn-info text-white" onclick="setData('<?= $file['gen'] ?>', '<?= $file['file'] ?>')" data-toggle="modal" data-target="#renameModal"><i class='bx bx-rename'></i> Redenumește</button>
                            <button type="button" class="btn btn-danger text-white" onclick="genHref('<?= $file['gen'] ?>')" data-toggle="modal" data-target="#deleteModal"><i class='bx bx-folder-minus'></i> Șterge</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="renameLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="renameLabel">Redenumește</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="modalRenameForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="fileName" class="col-form-label" id="labelCaptionRename"></label>
                            <input type="text" class="form-control" id="fileName">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Închide</button>
                        <button onclick="genAction()" type="submit" class="btn btn-primary">Modifică</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteLabel">Șterge</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fileName" class="col-form-label" id="labelCaptionDelete"><h3>Sunteți sigur că doriți să ștergeți acest fișier?</h3></label>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <a class="btn btn-danger" id="dela" href="#">Confirmă</a>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Anulează</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>