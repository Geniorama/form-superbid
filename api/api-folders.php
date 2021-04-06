<?php

require_once "./../controllers/folders.php";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $dropFolders = new Folder();
    if($_GET['folder']){
        $res = $dropFolders->obtenerCarpetas($_GET['folder']);
        echo json_encode($res);
    } else {
        echo "No hay un nombre de carpeta";
    }
}