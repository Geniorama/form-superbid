<?php

require_once "../terceros/dropbox/vendor/autoload.php";

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey = "ydj6licisq7rbr7";
$dropboxSecret = "mnmvh83u87lnvnv";
$dropboxToken = "7u3ACVFv4aAAAAAAAAAAAdG0qBUBbzPhTdxIGD6bOT1QQDldHuCQqFca4tgJgtZl";


$app = new DropboxApp($dropboxKey,$dropboxSecret,$dropboxToken);
$dropbox = new Dropbox($app);

$listFolderContents = $dropbox->listFolder("/");

if (!empty($_FILES)) {
    $nombre = uniqid();
    $documento = $_POST['documento'];
    $tempfile = $_FILES['file']['tmp_name'];
    $ext = explode(".",$_FILES['file']['name']);
    $ext = end($ext);
    $nombrecarpeta = "/".$documento;
    $nombredropbox = "/".$nombre.".".$ext;

    try {
        //$folder = $dropbox->createFolder("/". $documento);
        $file = $dropbox->simpleUpload($tempfile, $nombrecarpeta . $nombredropbox,['autorename' => true]);
       echo "Archivo subido";
    } catch (\exception $e) {
        print_r($e);
    }
}
