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

    date_default_timezone_set('America/Bogota');
    
    try {
        $datos = dataFolder('/Registro/' . $nombreArchivo);

        foreach ($campos_registro as $campo) {
            // deleteFile($campo, "Registro/" . $nombreArchivo );
            uploadFile($campo,"Registro/" . $nombreArchivo  );

            $to = "angelpublicista.1@gmail.com";
            $title = $idRadicado . " App Superbid";
            $msje = "Nueva actividad desde " . URL_SITE . "\n"  . "\n";
            // $msje .=  "Datos de subida:" . "\n";
            // $msje .= "Ruta archivo subido:" . "/" . $folder . $field["name_document"]  . "\n";
            $msje .= "Fecha: " . date("Y-m-d") . "\n" . "\n";
            $msje .= "Hora: " . date("H:i:s") . "\n" . "\n";
            $msje .= "Actividad exitosa" . "\n" . "\n";
            $msje .= "ID Radicado: " . $idRadicado . "\n" . "\n";
            // $msje .= "Política de privacidad: " . $GLOBALS['privacy_policies'];
            $headers = 'From: noreply@superbidcolombia.com' . "\r\n" .
            'Reply-To: noreply@superbidcolombia.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

            mail($to, $title, $msje, $headers);

            header('Location:'.URL_SITE.'/gracias.php?rad='. $idRadicado);
        }
       
    } catch (Exception $e) {
        $to = "angelpublicista.1@gmail.com";
        $title = $idRadicado . " App Superbid";
        $msje = "Nueva actividad desde " . URL_SITE . "\n"  . "\n";
        // $msje .=  "Datos de subida:" . "\n";
        // $msje .= "Ruta archivo subido:" . "/" . $folder . $field["name_document"]  . "\n";
        $msje .= "Fecha: " . date("Y-m-d") . "\n" . "\n";
        $msje .= "Hora: " . date("H:i:s") . "\n" . "\n";
        $msje .= "Actividad fallida" . "\n" . "\n";
        $msje .= "ID Radicado: " . $idRadicado . "\n" . "\n";
        // $msje .= "Política de privacidad: " . $GLOBALS['privacy_policies'];
        $headers = 'From: noreply@superbidcolombia.com' . "\r\n" .
        'Reply-To: noreply@superbidcolombia.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($to, $title, $msje, $headers);

        header('Location:'.URL_SITE.'/error.php?error='.$e->getMessage().'&rad='.$idRadicado.'');
    }

}
