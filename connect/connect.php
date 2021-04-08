<?php

require_once "./../config/config.php";
require_once "./../terceros/dropbox/vendor/autoload.php";

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

class ConnectDropbox{
    private $dropboxKey = DROPBOX_KEY;
    private $dropboxSecret = DROPBOX_SECRET;
    private $dropboxToken = DROPBOX_TOKEN;

    function connectDr(){
        $app = new DropboxApp($this->dropboxKey, $this->dropboxSecret, $this->dropboxToken);
        $dropbox = new Dropbox($app);

        return $dropbox;
    }
}