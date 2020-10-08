<?php

require_once "./../config/config.php";
require_once "../terceros/dropbox/vendor/autoload.php";

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
    $tipoDocumento = $_POST['rg-tipo-doc'];
    $numDocumento = $_POST['rg-num-doc'];
    $tipoPersona = $_POST['rg-tipo-persona'];

    //Archivos a subir
    $tiposArchivos = $_POST['rg-type-document'];

    $nombreSubasta = false;

    switch ($tiposArchivos) {
        case 'registro':
            $nombreCarpetaReg = "Documentos registro";

            //Documento identidad
            $doc_identidad = $_FILES['rg-field-documento']['tmp_name'];
            if($doc_identidad){
                $ext_doc_identidad = explode(".",$_FILES['rg-field-documento']['name']);
                $ext_doc_identidad = end($ext_doc_identidad);
            }

            //RUT
            $doc_rut = $_FILES['rg-field-rut']['tmp_name'];
            if($doc_rut){
                $ext_doc_rut = explode(".",$_FILES['rg-field-rut']['name']);
                $ext_doc_rut = end($ext_doc_rut);
            }

            if($tipoPersona == "p-juridica"){
                //Camara de comercio
                $doc_camara = $_FILES['rg-field-camara-comercio']['tmp_name'];
                if ($doc_camara) {
                    $ext_doc_camara = explode(".",$_FILES['rg-field-camara-comercio']['name']);
                    $ext_doc_camara = end($ext_doc_camara);
                }

                //Representante legal
                $doc_rep = $_FILES['rg-field-cedula-rep']['tmp_name'];
                if ($doc_rep) {
                    $ext_doc_rep = explode(".",$_FILES['rg-field-camara-comercio']['name']);
                    $ext_doc_rep = end($ext_doc_rep);
                }
            }
            break;

        case 'pagos':
            $nombreSubasta = $_POST["rg-nombre-subasta"];
            $nombreCarpetaReg = "Documentos pagos";

            $carta_tercero = $_FILES['rg-field-carta-tercero']['tmp_name'];
            $ext_carta_tercero = explode(".",$_FILES['rg-field-carta-tercero']['name']);
            $ext_carta_tercero = end($ext_carta_tercero);

            break;
        
        case 'garantia':
            $nombreCarpetaReg = "Documentos garantia";
            break;

        case 'retiros':
            $nombreCarpetaReg = "Documentos retiros";
            break;
        
        default:
            # code...
            break;
    }


    //Renombrando archivos
    $nombrecarpeta = "/". $tipoDocumento . "_" .$numDocumento;

    $nombreCedula = "/cedula_ciudadania.".$ext_doc_identidad;
    $nombreRut = "/rut.".$ext_doc_rut;

    try {
        //$folder = $dropbox->createFolder("/". $documento);
        if($numDocumento){
           $file = $dropbox->simpleUpload($doc_identidad, $nombrecarpeta . "/" . $nombreCarpetaReg . $nombreCedula,['autorename' => true]);
           $file2 = $dropbox->simpleUpload($doc_rut, $nombrecarpeta . "/" . $nombreCarpetaReg . $nombreRut,['autorename' => true]);
           if($nombreSubasta){
             $file3 = $dropbox->simpleUpload($tempfile2, $nombrecarpeta . "/" . $nombreSubasta . "/" . $carpetaGarantias . $nombredropbox2,['autorename' => true]);
           }
        }
        
        
       echo "Archivo subido";
    } catch (\exception $e) {
        print_r($e);
    }
}
