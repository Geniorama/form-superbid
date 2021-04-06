<?php

require_once "./../connect/connect.php";

$database = new ConnectDropbox();
$dropbox = $database->connectDr();

var_dump($dropbox);

// class ApiFolders{
//     function getAll(){
//         $folders = new Folder();

//         $allFolders = array();
//         $allFolders["items"] = array();

//         $res = $folders->obtenerCarpetas();

//         foreach($res as $itemFold){
//             $item = array(
//                 'nombre' => $itemFold['nameFolder']
//             );

//             array_push($allFolders["items"], $item);
//         }
        
//         echo json_encode($allFolders);
//     }
// }