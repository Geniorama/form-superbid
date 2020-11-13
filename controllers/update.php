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

$listFolderContents = $dropbox->listFolder("/");

if (!empty($_FILES)) {
    //Datos carpeta general
    $tipoDocumento = $_POST['upt-tipo-doc'];
    $numDocumento = $_POST['upt-num-doc'];
    $tipoPersona = $_POST['upt-tipo-persona'];

    //Renombrando archivos
    $nombrecarpeta = "/". $tipoDocumento . "_" .$numDocumento;

    $field_documento = tempFile('upt-field-documento', 'cedula_ciudadania');
    $field_rut = tempFile('upt-field-rut', 'nit');

    if($tipoPersona == "p-juridica"){
        $field_camara_comercio = tempFile('upt-field-camara-comercio', 'camara_comercio');
        $field_rep = tempFile('upt-field-cedula-rep', 'cedula_rep_legal');
        $campos_registro = array($field_documento, $field_rut, $field_camara_comercio, $field_rep);
    } else{
        $campos_registro = array($field_documento, $field_rut);
    }


    try {
        $datos = dataFolder();

        if($numDocumento && $datos->id){
            foreach ($campos_registro as $campo) {
                deleteFile($campo, "Documentos registro");
                uploadFile($campo, "Documentos registro");
            }
        }
        
       header('Location:https://www.superbidcolombia.com/formulario-documentos/gracias.html');
    } catch (\exception $e) {
       //print_r($e);
       header('Location:https://www.superbidcolombia.com/formulario-documentos/error.html');
    }
}
