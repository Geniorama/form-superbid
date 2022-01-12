<?php
require_once "./../connect/connect.php";

// Renombrando archivos subidos
if(!function_exists('tempFile')){
    function tempFile($file, $name){
        $document = $_FILES[$file]['tmp_name'];
        if ($document) {
            $ext = explode(".",$_FILES[$file]['name']);
            $ext = end($ext);
    
            $name_document = "/" . $name . "_" . $GLOBALS['nombreArchivo'] . "." . $ext;
    
            return array("document" => $document, "ext" => $ext, "name_document" => $name_document);
        }
    }
}

// Subir archivos a Dropbox
if(!function_exists('uploadFile')){
    function uploadFile($field, $folder){
        $database = new ConnectDropbox();
        $dropbox = $database->connectDr();
        
        if($field && $folder){
            $file = $dropbox->simpleUpload($field["document"], "/" . $folder . $field["name_document"],['autorename' => true]);

            date_default_timezone_set('America/Bogota');

            return true;
        }
    }
}


if(!function_exists('deleteFile')){
    function deleteFile($field, $folder){
        $database = new ConnectDropbox();
        $dropbox = $database->connectDr();

        if($field){
            $fileMetaData = $dropbox->getMetadata("/" . $folder . $field["name_document"]);
            if($fileMetaData->id){
                $file = $dropbox->delete("/" . $folder . $field["name_document"]);
            } else {
                die();
            }
        }
    }
}

if(!function_exists('dataFolder')){
    function dataFolder($folderName){
        $database = new ConnectDropbox();
        $dropbox = $database->connectDr();
        
        if($folderName){
            $fileMetaData = $dropbox->getMetadata($folderName);
            return $fileMetaData;
        }
    }
}