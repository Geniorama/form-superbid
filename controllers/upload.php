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
    $tipoDocumento = $_POST['rg-tipo-doc'];
    $numDocumento = $_POST['rg-num-doc'];
    $tipoPersona = $_POST['rg-tipo-persona'];

    //Archivos a subir
    $tiposArchivos = $_POST['rg-type-document'];

    $nombreSubasta = false;

    //Renombrando archivos
    $nombrecarpeta = "/". $tipoDocumento . "_" .$numDocumento;

    switch ($tiposArchivos) {
        case 'registro':
            $field_documento = tempFile('rg-field-documento', 'cedula_ciudadania');
            $field_rut = tempFile('rg-field-rut', 'nit');

            if($tipoPersona == "p-juridica"){
                $field_camara_comercio = tempFile('rg-field-camara-comercio', 'camara_comercio');
                $field_rep = tempFile('rg-field-cedula-rep', 'cedula_rep_legal');
            }

            $campos_registro = array($field_documento, $field_rut);
            break;

        case 'pagos':
            $nombreSubasta = $_POST["rg-nombre-subasta"];
            $nombreCarpetaReg = "Documentos pagos";

            $field_carta_tercero = tempFile('rg-field-carta-tercero', 'carta_tercero');
            $field_soporte_pago_lote = tempFile('rg-field-soporte-pago-lote', 'soporte_pago_lote');
            $field_soporte_pago_comision = tempFile('rg-field-soporte-pago-comision', 'soporte_pago_comision');
            $field_soporte_pago_traspasos = tempFile('rg-field-soporte-pago-traspasos', 'soporte_pago_traspasos');
            $field_soporte_pago_fianza = tempFile('rg-field-soporte-pago-fianza', 'soporte_pago_fianza');

            $campos_pagos = array($field_carta_tercero, $field_soporte_pago_lote, $field_soporte_pago_comision, $field_soporte_pago_traspasos, $field_soporte_pago_fianza);

            break;
        
        case 'garantia':
            $nombreSubasta = $_POST["rg-nombre-subasta"];
            $field_soporte_garantia = tempFile('rg-field-soporte-garantia', 'soporte_garantia');
            $field_cert_bancaria = tempFile('rg-field-certificacion-bancaria', 'certificacion_bancaria');
            $field_docs_garantias = tempFile('rg-field-documentos-garantias', 'documentos_garantias');

            $campos_garantia = array($field_soporte_garantia, $field_cert_bancaria, $field_docs_garantias);
            
            break;

        case 'retiros':
            $nombreSubasta = $_POST["rg-nombre-subasta"];
            $field_planilla_aportes = tempFile('rg-field-planilla-aportes', 'planilla_aportes');
            $field_poliza = tempFile('rg-field-poliza', 'poliza');
            $field_rtm = tempFile('rg-field-rtm', 'rtm');
            $field_soat = tempFile('rg-field-soat', 'soat');

            $campos_retiros = array($field_planilla_aportes, $field_poliza, $field_rtm, $field_soat);
            break;
        
        default:
            # code...
            break;
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

    

    try {
        //$folder = $dropbox->createFolder("/". $documento);
        if($numDocumento){
            foreach ($campos_registro as $campo) {
                uploadFile($campo,"Documentos registro");
            }

            if($nombreSubasta){
                if($campos_pagos){
                    foreach ($campos_pagos as $campo_pago) {
                        uploadFile($campo_pago, $nombreSubasta . "/Documentos pagos");
                    }
                }

                if($campos_garantia){
                    foreach ($campos_garantia as $campo_garantia) {
                        uploadFile($campo_garantia, $nombreSubasta . "/Documentos garantia");
                    }
                }

                if($campos_retiros){
                    foreach ($campos_retiros as $campo_retiros) {
                        uploadFile($campo_retiros, $nombreSubasta . "/Documentos retiros");
                    }
                }
            }
        }
        
        
       echo "Archivo subido";
       //header('Location:/gracias.html');
    } catch (\exception $e) {
        print_r($e);
    }
}
