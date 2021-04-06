<?php

require_once "./../config/config.php";
require_once "../terceros/dropbox/vendor/autoload.php";
require_once "./../helpers/helpers.php";

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey = $api_config["dropboxKey"];
$dropboxSecret = $api_config["dropboxSecret"];
$dropboxToken = $api_config["dropboxToken"];


$app = new DropboxApp($dropboxKey,$dropboxSecret,$dropboxToken);
$dropbox = new Dropbox($app);

$listFolderContents = $dropbox->listFolder("/Pagos");

$items = $listFolderContents->getItems();

$arrItems = array();

foreach($items as $item){
    array_push($arrItems, ["nameFolder" => $item->getName()]);
}

echo json_encode($arrItems);

