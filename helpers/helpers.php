<?php
require_once "./../connect/connect.php";

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

if(!function_exists('uploadFile')){
    function uploadFile($field, $folder){
        $database = new ConnectDropbox();
        $dropbox = $database->connectDr();
        
        if($field && $folder){
            $file = $dropbox->simpleUpload($field["document"], "/" . $folder . $field["name_document"],['autorename' => true]);
            return true;
        }
    }
}


if(!function_exists('deleteFile')){
    function deleteFile($field, $folder){
        global $dropbox;
        global $nombrecarpeta;
        if($field){
            $file = $dropbox->delete($nombrecarpeta . "/" . $folder . $field["name_document"]);
            ///cedula-ciudadania_213213213/Documentos registro/cedula_ciudadania.png
        }
    }
}

if(!function_exists('dataFolder')){
    function dataFolder(){
        global $dropbox;
        global $nombrecarpeta;
        if($nombrecarpeta){
            $file = $dropbox->getMetadata($nombrecarpeta);
            return $file;
            ///cedula-ciudadania_213213213/Documentos registro/cedula_ciudadania.png
        }
    }
}