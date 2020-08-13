<?php

require_once "../terceros/dropbox/vendor/autoload.php";

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey = "mw8jzg5r0pv579f";
$dropboxSecret = "nheaxswzx15gupt";
$dropboxToken = "sl.AfpftTtgZarcsTkYfo4EGSFH0AxIP2Kcj0q63u8IU_1lyQSl6yes08pwCL7lxGtWTL5HShVx6ZZKH9CY6oHoyveXrN56KQSWbjYLD1NIPhOs45Auof3e2kHxObt2eQXT3lpBuo0";


$app = new DropboxApp($dropboxKey,$dropboxSecret,$dropboxToken);
$dropbox = new Dropbox($app);

if (!empty($_FILES)) {
    $nombre = uniqid();
    $tempfile = $_FILES['file']['tmp_name'];
    $ext = explode(".",$_FILES['file']['name']);
    $ext = end($ext);
    $nombredropbox = "/".$nombre.".".$ext;

    try {
       $file = $dropbox->simpleUpload($tempfile,$nombredropbox,['autorename' => true]);
       echo "Archivo subido";
    } catch (\exception $e) {
        print_r($e);
    }
}
