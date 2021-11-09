<?php

require_once "./../helpers/helpers.php";

if (!empty($_FILES) && $_SERVER['REQUEST_METHOD'] == "POST") {
    //Datos carpeta general
    $tipoDocumento = $_POST['upt-tipo-doc'];
    $numDocumento = $_POST['upt-num-doc'];
    $tipoPersona = $_POST['upt-tipo-persona'];

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

    $field_documento = tempFile('upt-field-documento', 'CC');
    $field_rut = tempFile('upt-field-rut', 'NIT');

    if($tipoPersona == "p-juridica"){
        $field_camara_comercio = tempFile('upt-field-camara-comercio', 'CCMO');
        $field_rep = tempFile('upt-field-cedula-rep', 'CC-REP');
        $campos_registro = array($field_documento, $field_rut, $field_camara_comercio, $field_rep);
    } else{
        $campos_registro = array($field_documento, $field_rut);
    }


    try {
        $datos = dataFolder('/Registro/' . $nombreArchivo);

        foreach ($campos_registro as $campo) {
            // deleteFile($campo, "Registro/" . $nombreArchivo );
            uploadFile($campo,"Registro/" . $nombreArchivo  );

            header('Location:'.URL_SITE.'/gracias.php?rad='. $idRadicado);
        }
       
    } catch (Exception $e) {
        header('Location:'.URL_SITE.'/error.php?error='.$e->getMessage().'&rad='.$idRadicado.'');
    }

}
