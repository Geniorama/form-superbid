<?php

require_once "./../helpers/helpers.php";

if (!empty($_FILES) && $_SERVER['REQUEST_METHOD'] == "POST") {

    if(isset($_POST['rg-tipo-doc'])){
        //Datos carpeta general
        $tipoDocumento = filter_var($_POST['rg-tipo-doc'], FILTER_SANITIZE_STRING);
        $numDocumento = filter_var($_POST['rg-num-doc'], FILTER_SANITIZE_NUMBER_INT);
        $tipoPersona = filter_var($_POST['rg-tipo-persona'], FILTER_SANITIZE_STRING);

        if(isset($_POST['rg-nombre-empresa'])){
            $nombreEmpresa = filter_var($_POST['rg-nombre-empresa'], FILTER_SANITIZE_STRING);
        }
        
        //Archivos a subir
        $tiposArchivos = $_POST['rg-type-document'];

         // Campo Nombre subasta
        if($tiposArchivos != "creacion" && $tiposArchivos != "registro"){
            $nombreSubasta = filter_var($_POST["rg-nombre-subasta"], FILTER_SANITIZE_STRING);   
        }
    } else {
        $tipoDocumento = $_POST['wrr-tipo-doc'];
        $numDocumento = $_POST['wrr-num-doc'];
        $tipoPersona = $_POST['wrr-tipo-persona'];
        // $nombreEmpresa = $_POST['wrr-nombre-empresa'];

        //Archivos a subir
        $tiposArchivos = $_POST['wrr-type-document'];

        $nombreSubasta = filter_var($_POST["wrr-nombre-subasta"], FILTER_SANITIZE_STRING);   
    }
    

    //Política privacidad
    $privacy_policies = $_POST['privacy-policy'];

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
    
    date_default_timezone_set('America/Bogota');

    if($numDocumento){
        switch ($tiposArchivos) {
            case 'registro':
    
                $field_documento = tempFile('rg-field-documento', 'CC');
                $field_rut = tempFile('rg-field-rut', 'RUT');
    
                if($tipoPersona == "p-juridica"){
                    $field_camara_comercio = tempFile('rg-field-camara-comercio', 'CCMO');
                    $field_rep = tempFile('rg-field-cedula-rep', 'CC-REP');
                    $campos_registro = array($field_documento, $field_rut, $field_camara_comercio, $field_rep);
                } else{
                    $campos_registro = array($field_documento, $field_rut);
                }
    
                foreach ($campos_registro as $campo) {
                    uploadFile($campo,"Registro/" . $nombreArchivo  );
                }
    
                break;
            
            case 'creacion':
    
                $field_creacion_cliente = tempFile('rg-field-creacion-cliente', 'FORMATO');
                $field_anexo_1 = tempFile('rg-field-anexo-1', 'ANEXO1');
                $field_anexo_2 = tempFile('rg-field-anexo-2', 'ANEXO2');
                $field_anexo_3 = tempFile('rg-field-anexo-3', 'ANEXO3');
                $field_anexo_4 = tempFile('rg-field-anexo-4', 'ANEXO4');
                $field_anexo_5 = tempFile('rg-field-anexo-5', 'ANEXO5');
    
                $campos_creacion = array($field_creacion_cliente, $field_anexo_1, $field_anexo_2, $field_anexo_3, $field_anexo_4, $field_anexo_5);
    
                foreach ($campos_creacion as $campo_creacion) {
                    uploadFile($campo_creacion, "Creacion de cliente/" . $nombreEmpresa . "/" . $nombreArchivo);
                }
                
                break;
    
            case 'pagos':
    
                $field_carta_tercero = tempFile('rg-field-carta-tercero', 'CT');
                $field_soporte_pago_lote = tempFile('rg-field-soporte-pago-lote', 'SPL');
                $field_soporte_pago_comision = tempFile('rg-field-soporte-pago-comision', 'SPC');
                $field_soporte_pago_traspasos = tempFile('rg-field-soporte-pago-traspasos', 'SPT');
                $field_soporte_pago_fianza = tempFile('rg-field-soporte-pago-fianza', 'SPF');
    
                $campos_pagos = array($field_carta_tercero, $field_soporte_pago_lote, $field_soporte_pago_comision, $field_soporte_pago_traspasos, $field_soporte_pago_fianza);
    
                foreach ($campos_pagos as $campo_pago) {
                    uploadFile($campo_pago, "Pagos/" . $nombreSubasta . "/" . $nombreArchivo);
                }
    
                break;
            
            case 'garantia':

                if(isset($_POST['rg-tipo-doc'])){
                    $field_soporte_garantia = tempFile('rg-field-soporte-garantia', 'SG');
                    $field_cert_bancaria = tempFile('rg-field-certificacion-bancaria', 'CB');
                    $field_docs_garantias = tempFile('rg-field-documentos-garantias', 'DG');
                    $field_parafiscales = tempFile('rg-field-parafiscales', 'PF');
                } else {
                    $field_soporte_garantia = tempFile('wrr-field-soporte-garantia', 'SG');
                    $field_cert_bancaria = tempFile('wrr-field-certificacion-bancaria', 'CB');
                    $field_docs_garantias = tempFile('wrr-field-documentos-garantias', 'DG');
                    $field_parafiscales = tempFile('wrr-field-parafiscales', 'PF');
                }
    
                $campos_garantia = array($field_soporte_garantia, $field_cert_bancaria, $field_docs_garantias, $field_parafiscales);
                
                foreach ($campos_garantia as $campo_garantia) {
                    uploadFile($campo_garantia, "Garantia/" . $nombreSubasta . "/" . $nombreArchivo);
                }
    
                break;
    
            case 'retiros':
                $field_planilla_aportes = tempFile('rg-field-planilla-aportes', 'PA');
                $field_poliza = tempFile('rg-field-poliza', 'POL');
                $field_rtm = tempFile('rg-field-rtm', 'RTM');
                $field_soat = tempFile('rg-field-soat', 'SOAT');
    
                $campos_retiros = array($field_planilla_aportes, $field_poliza, $field_rtm, $field_soat);

                foreach ($campos_retiros as $campo_retiros) {
                    uploadFile($campo_retiros, "Retiros/" . $nombreSubasta . "/" . $nombreArchivo);
                }
                break;
            default:
                # code...
                break;
        }
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
    } else {

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

        header('Location:'.URL_SITE.'/error.php?rad='. $idRadicado . "&error=Debe diligenciar un número de documento");
    }
    
}
