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

            date_default_timezone_set('America/Bogota');

            $to = "soporte@geniorama.site";
            $title = "Archivos subidos - Dropbox Api";
            $msje = "Un nuevo archivo ha sido subido a la nube desde " . URL_SITE . "\n"  . "\n";
            $msje .=  "Datos de subida:" . "\n";
            $msje .= "Ruta archivo subido:" . "/" . $folder . $field["name_document"]  . "\n";
            $msje .= "Fecha: " . date("Y-m-d H:i:s") . "\n" . "\n";
            $msje .= "Política de privacidad: " . $GLOBALS['privacy_policies'];
            $headers = 'From: documentos@superbid.com' . "\r\n" .
            'Reply-To: documentos@superbid.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

            mail($to, $title, $msje, $headers);

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