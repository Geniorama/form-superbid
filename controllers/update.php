<?php

require_once "./../helpers/helpers.php";
require_once "./../config/config.php";

if (!empty($_FILES) && $_SERVER['REQUEST_METHOD'] == "POST") {
    //Datos carpeta general
    $tipoDocumento = $_POST['upt-tipo-doc'];
    $numDocumento = $_POST['upt-num-doc'];
    $tipoPersona = $_POST['upt-tipo-persona'];
    $archivoAct = array();

    if($tipoDocumento == "cedula-ciudadania"){
        $abrev = "CC";
    } elseif($tipoDocumento == "nit"){
        $abrev = "NIT";
    } elseif($tipoDocumento == "cedula-extranjeria"){
        $abrev = "CE";
    } elseif($tipoDocumento == "pasaporte"){
        $abrev = "PAS";
    }

    //Renombrando archivos
    $nombreArchivo = $abrev . "_" .$numDocumento;

    $idRadicado = uniqid($abrev . "-" .$numDocumento . "-");

    $field_documento = [
        'tempFile' => tempFile('upt-field-documento', 'CC'),
        'field' => 'Documento identificación'
    ];

    $field_rut = [
        'tempField' => tempFile('upt-field-rut', 'RUT'),
        'field' => 'RUT'
    ];

    if($tipoPersona == "p-juridica"){
        $field_camara_comercio = [
            'tempFile' => tempFile('upt-field-camara-comercio', 'CCMO'),
            'field' => 'Cámara de Comercio'
        ];
        $field_rep = [
            'tempFile' => tempFile('upt-field-cedula-rep', 'CC-REP'),
            'field' => 'Documento Representante Legal'
        ];

        $campos_registro = array($field_documento, $field_rut, $field_camara_comercio, $field_rep);
    } else{
        $campos_registro = array($field_documento, $field_rut);
    }

    date_default_timezone_set('America/Bogota');
    
    try {
        $datos = dataFolder('/Registro/' . $nombreArchivo);

        foreach ($campos_registro as $campo) {
            if($campo['tempFile'] && $campo['field']){
                $archivoAct = $campo['field'];
                array_push($archivoAct, $campo['field']);
                uploadFile($campo['tempFile'], "Registro/" . $nombreArchivo);
            }
        }

        $archivoActStr = implode(', ', $archivoAct);

        $to = EMAIL_ADMIN;
        $title = $idRadicado . " App Superbid";
        $msje = "Nueva actividad desde " . URL_SITE . "\n"  . "\n";
        $msje .= "DATOS DE SUBIDA" . "\n";
        $msje .= "Etapa: Actualización Registro" . "\n";
        $msje .= "Documento(s) cargado(s): " . $archivoActStr  . "\n";
        $msje .= "Fecha: " . date("Y-m-d") . "\n";
        $msje .= "Hora: " . date("H:i:s") . "\n";
        $msje .= "Actividad exitosa" . "\n" . "\n";
        $msje .= "ID Radicado: " . $idRadicado . "\n" . "\n";
        // $msje .= "Política de privacidad: " . $GLOBALS['privacy_policies'];
        $headers = 'From: noreply@superbidcolombia.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($to, $title, $msje, $headers);

        header('Location:'.URL_SITE.'/gracias.php?rad='. $idRadicado . '&t-doc=' . $tipoDocumento . '&n-doc=' . $numDocumento . '&etapa=Actualización Registro' . '&archivo=' . $archivoAct);
       
    } catch (Exception $e) {
        $to = EMAIL_ADMIN;
        $title = $idRadicado . " App Superbid";
        $msje = "Nueva actividad desde " . URL_SITE . "\n"  . "\n";
        $msje .= "Fecha: " . date("Y-m-d") . "\n" . "\n";
        $msje .= "Hora: " . date("H:i:s") . "\n" . "\n";
        $msje .= "Actividad fallida" . "\n" . "\n";
        $msje .= "ID Radicado: " . $idRadicado . "\n" . "\n";
        // $msje .= "Política de privacidad: " . $GLOBALS['privacy_policies'];
        $headers = 'From: notification <noreply@superbidcolombia.com>' . "\r\n" .
        'Reply-To: noreply@superbidcolombia.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($to, $title, $msje, $headers);

        header('Location:'.URL_SITE.'/error.php?error='.$e->getMessage().'&rad='.$idRadicado.'');
    }

}
