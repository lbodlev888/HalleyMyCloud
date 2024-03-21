<?php
    require "connect.php";
    if(!isset($_SESSION['user']) || empty($_SESSION['user']) || !isset($_GET['file']) || empty($_GET['file']))
        header("Location: login.php");
    $params = array('download', 'ren', 'del');
    $val = array(3);
    $index = 0;
    for($i = 0; $i < 3; $i++)
    {
        if(isset($_GET[$params[$i]])) {
            $val[$i] = 1;
            $index = $i;
        }
        else $val[$i] = 0;
    }
    $count = 0;
    foreach($val as $v)
        if($v == 1) $count++;
    if($count > 1) header("Location: index.php?status=invalidrequest");

    switch($params[$index])
    {
        case 'download':
            $sql = "CALL UpdateDownTimes('".$_GET['file']."')";
            $result = ($conn->query($sql))->fetch_assoc();
            $size = filesize("/var/www/uploads/".$_GET['file']);
            if($result['stat'] == 'Operațiunea a reușit') {
                $downloadFileName = $result['fileName'];
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($downloadFileName));
                header('Content-Transfer-Encoding: binary');
                header('Connection: Keep-Alive');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . $size);
                echo file_get_contents("/var/www/uploads/".$_GET['file']);
            }
            else header('Location: index.php?status=error');
            break;
        case 'ren':
            if(!isset($_GET['newFileName']) || empty($_GET['newFileName'])) header("Location: index.php?status=invalidrequest");
            $fileId = $_GET['file'];
            $newName = $_GET['newFileName'];
            $newNameArray = explode('.', $newName);
            $ext = explode('.', $fileId)[1];
            if($newNameArray[1] != $ext) $newName = $newNameArray[0].'.'.$ext;
            $sql = "UPDATE files SET fileName='$newName' WHERE genName='$fileId'";
            $conn->query($sql);
            header("Location: index.php?status=renameSuccess");
            break;
        case 'del':
            unlink("/var/www/uploads/".$_GET['file']);
            $sql = "DELETE FROM files WHERE genName='".$_GET['file']."'";
            $conn->query($sql);
            header("Location: index.php?status=deleteSuccess");
            break;
    }
?>