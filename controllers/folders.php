<?php

require_once "./../connect/connect.php";

$database = new ConnectDropbox();
$dropbox = $database->connectDr();

class Folder {
    function obtenerCarpetas($carpeta){
        global $dropbox;
        $listFolderContents = $dropbox->listFolder("/" . $carpeta);

        $items = $listFolderContents->getItems();

        $arrItems = [];

        foreach($items as $item){
            array_push($arrItems, ["nameFolder" => $item->getName()]);
        }

        return $arrItems;
    }
}



