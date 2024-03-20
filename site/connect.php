<?php
    session_start();
    date_default_timezone_set("Europe/Bucharest");
    error_reporting(0);
    try {
        $conn = new mysqli('baza', 'root', 'mypassword', 'MyCloudDatabase');
    } catch(Exception $e) {
        ?>
        <style>
            h2 { text-align: center; }
        </style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <div class="p-3"><h2>Nu s-a putut conecta cu baza de date</h2></div>
        <?php exit;
    }
?>