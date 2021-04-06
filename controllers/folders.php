<?php

require_once "./../config/config.php";
require_once "./../terceros/dropbox/vendor/autoload.php";
require_once "./../helpers/helpers.php";



$dropboxKey = $api_config["dropboxKey"];
$dropboxSecret = $api_config["dropboxSecret"];
$dropboxToken = $api_config["dropboxToken"];

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$app = new DropboxApp($dropboxKey,$dropboxSecret,$dropboxToken);
$dropbox = new Dropbox($app);

class Folder {
    function obtenerCarpetas(){
        global $dropbox;
        $listFolderContents = $dropbox->listFolder("/Pagos");

        $items = $listFolderContents->getItems();

        $arrItems = [];

        foreach($items as $item){
            array_push($arrItems, ["nameFolder" => $item->getName()]);
        }

        return $arrItems;
    }
}



