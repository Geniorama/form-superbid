<?php
if(!function_exists('tempFile')){
    function tempFile($file, $name){
        $document = $_FILES[$file]['tmp_name'];
        if ($document) {
            $ext = explode(".",$_FILES[$file]['name']);
            $ext = end($ext);
    
            $name_document = "/". $name . "." . $ext;
    
            return array("document" => $document, "ext" => $ext, "name_document" => $name_document);
        }
    }
}

if(!function_exists('uploadFile')){
    function uploadFile($field, $folder){
        global $dropbox;
        global $nombrecarpeta;
        if($field){
            $file = $dropbox->simpleUpload($field["document"], $nombrecarpeta . "/" . $folder . $field["name_document"],['autorename' => true]);
        }
    }
}