<?php

require_once "./../helpers/helpers.php";
require_once "./../config/config.php";

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
        if($tiposArchivos != "creacion" && $tiposArchivos != "registro" && $tiposArchivos != "registro-act"){
            $nombreSubasta = filter_var($_POST["rg-nombre-subasta"], FILTER_SANITIZE_STRING);   
        }
    } else {
        $tipoDocumento = $_POST['wrr-tipo-doc'];
        $numDocumento = $_POST['wrr-num-doc'];
        $tipoPersona = $_POST['wrr-tipo-persona'];

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
    $nombreCarpeta;
    $archivoSubido = array();
    $archivoAct = array();
    
    date_default_timezone_set('America/Bogota');

    if($numDocumento){
        switch ($tiposArchivos) {
            case 'registro-act':
    
                $field_documento_act = [
                    'tempFile' => tempFile('rg-field-documento_act', 'CC'),
                    'field' => 'Documento identificación'
                ];
                $field_rut_act = [
                    'tempFile' => tempFile('rg-field-rut_act', 'RUT'),
                    'field' => 'RUT'
                ];

                $nombreCarpeta = "Registro";
    
                if($tipoPersona == "p-juridica"){
                    $field_camara_comercio_act = [
                        'tempFile' => tempFile('rg-field-camara-comercio_act', 'CCMO'),
                        'field' => 'Cámara de Comercio'
                    ];

                    $field_rep_act = [
                        'tempFile' => tempFile('rg-field-cedula-rep_act', 'CC-REP'),
                        'field' => 'Documento Representante Legal'
                    ];
                    
                    $campos_registro_act = array($field_documento_act, $field_rut_act, $field_camara_comercio_act, $field_rep_act);
                } else{
                    $campos_registro_act = array($field_documento_act, $field_rut_act);
                }

                try {
                    $datos = dataFolder('/Registro/' . $nombreArchivo);
            
                    foreach ($campos_registro_act as $campo) {
                        if($campo['tempFile'] && $campo['field']){
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
            
                    header('Location:'.URL_SITE.'/gracias.php?rad='. $idRadicado . '&t-doc=' . $tipoDocumento . '&n-doc=' . $numDocumento . '&etapa=Actualización Registro' . '&archivo=' . $archivoActStr);
                   
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
    
                die;
            case 'registro':
    
                $field_documento = [
                    'tempFile' => tempFile('rg-field-documento', 'CC'),
                    'field' => 'Documento identificación'
                ];
                $field_rut = [
                    'tempFile' => tempFile('rg-field-rut', 'RUT'),
                    'field' => 'RUT'
                ];

                $nombreCarpeta = "Registro";
    
                if($tipoPersona == "p-juridica"){
                    $field_camara_comercio = [
                        'tempFile' => tempFile('rg-field-camara-comercio', 'CCMO'),
                        'field' => 'Cámara de Comercio'
                    ];

                    $field_rep = [
                        'tempFile' => tempFile('rg-field-cedula-rep', 'CC-REP'),
                        'field' => 'Documento Representante Legal'
                    ];
                    
                    $campos_registro = array($field_documento, $field_rut, $field_camara_comercio, $field_rep);
                } else{
                    $campos_registro = array($field_documento, $field_rut);
                }
    
                foreach ($campos_registro as $campo) {
                    if($campo['tempFile'] && $campo['field']){
                        array_push($archivoSubido, $campo['field']);
                        uploadFile($campo['tempFile'], "Registro/" . $nombreArchivo);
                    }
                }
    
                break;

            
            case 'creacion':
    
                $nombreCarpeta = "Creación";

                $field_creacion_cliente = [
                    'tempFile' => tempFile('rg-field-creacion-cliente', 'FORMATO'),
                    'field' => 'Formato creación de cliente'
                ];

                $field_anexo_1 = [
                    'tempFile' => tempFile('rg-field-anexo-1', 'ANEXO1'),
                    'field' => 'Creación - Anexo 1'
                ];

                $field_anexo_2 = [
                    'tempFile' => tempFile('rg-field-anexo-2', 'ANEXO2'),
                    'field' => 'Creación - Anexo 2'
                ];

                $field_anexo_3 = [
                    'tempFile' => tempFile('rg-field-anexo-3', 'ANEXO3'),
                    'field' => 'Creación - Anexo 3'
                ];

                $field_anexo_4 = [
                    'tempFile' => tempFile('rg-field-anexo-4', 'ANEXO4'),
                    'field' => 'Creación - Anexo 4'
                ];

                $field_anexo_5 = [
                    'tempFile' => tempFile('rg-field-anexo-5', 'ANEXO5'),
                    'field' => 'Creación - Anexo 5'
                ];
    
                $campos_creacion = array($field_creacion_cliente, $field_anexo_1, $field_anexo_2, $field_anexo_3, $field_anexo_4, $field_anexo_5);

                foreach ($campos_creacion as $campo_creacion) {
                    if($campo_creacion['tempFile'] && $campo_creacion['field']){
                        array_push($archivoSubido, $campo_creacion['field']);
                        uploadFile($campo_creacion['tempFile'], "Creacion de cliente/" . $nombreEmpresa . "/" . $nombreArchivo);
                    } 
                }
                
                break;
    
            case 'pagos':
    
                $nombreCarpeta = "Pagos";

                $field_carta_tercero = [
                    'tempFile' => tempFile('rg-field-carta-tercero', 'CT'),
                    'field' => 'Carta Tercero'
                ];

                $field_soporte_pago_lote = [
                    'tempFile' => tempFile('rg-field-soporte-pago-lote', 'SPL'),
                    'field' => 'Soporte pago lote'
                ];

                $field_soporte_pago_comision = [
                    'tempFile' => tempFile('rg-field-soporte-pago-comision', 'SPC'),
                    'field' => 'Soporte pago comision'
                ];

                $field_soporte_pago_traspasos = [
                    'tempFile' => tempFile('rg-field-soporte-pago-traspasos', 'SPT'),
                    'field' => 'Soporte pago traspasos'
                ];

                $field_soporte_pago_fianza = [
                    'tempFile' => tempFile('rg-field-soporte-pago-fianza', 'SPF'),
                    'field' => 'Soporte pago fianza'
                ];
    
                $campos_pagos = array($field_carta_tercero, $field_soporte_pago_lote, $field_soporte_pago_comision, $field_soporte_pago_traspasos, $field_soporte_pago_fianza);
    
                foreach ($campos_pagos as $campo_pago) {
                    if($campo_pago['tempFile'] && $campo_pago['field']){
                        array_push($archivoSubido, $campo_pago['field']);
                        uploadFile($campo_pago['tempFile'], "Pagos/" . $nombreSubasta . "/" . $nombreArchivo);
                    } 
                }
    
                break;
            
            case 'garantia':

                $nombreCarpeta = "Garantía";

                if(isset($_POST['rg-tipo-doc'])){
                    $field_soporte_garantia = [
                        'tempFile' => tempFile('rg-field-soporte-garantia', 'SG'),
                        'field' => 'Soporte Garantía'
                    ];

                    $field_cert_bancaria = [
                        'tempFile' => tempFile('rg-field-certificacion-bancaria', 'CB'),
                        'field'=> 'Certificación bancaria'
                    ];

                    $field_docs_garantias = [
                        'tempFile' => tempFile('rg-field-documentos-garantias', 'DG'),
                        'field' => 'Documentos Garantías'
                    ];

                    $field_parafiscales = [
                        'tempFile' => tempFile('rg-field-parafiscales', 'PF'),
                        'field' => 'Parafiscales'
                    ];
                } else {
                    $field_soporte_garantia = [
                        'tempFile' => tempFile('wrr-field-soporte-garantia', 'SG'),
                        'field' => 'Soporte Garantía'
                    ];
                    
                    $field_cert_bancaria = [
                        'tempFile' => tempFile('wrr-field-certificacion-bancaria', 'CB'),
                        'field'=> 'Certificación bancaria'
                    ];
                    
                    $field_docs_garantias = [
                        'tempFile' => tempFile('wrr-field-documentos-garantias', 'DG'),
                        'field' => 'Documentos Garantías'
                    ];

                    $field_parafiscales = [
                        'tempFile' => tempFile('wrr-field-parafiscales', 'PF'),
                        'field' => 'Parafiscales'
                    ];
                }
    
                $campos_garantia = array($field_soporte_garantia, $field_cert_bancaria, $field_docs_garantias, $field_parafiscales);

                foreach ($campos_garantia as $campo_garantia) {
                    if($campo_garantia['tempFile'] && $campo_garantia['field']){
                        array_push($archivoSubido, $campo_garantia['field']);
                        uploadFile($campo_garantia['tempFile'], "Garantia/" . $nombreSubasta . "/" . $nombreArchivo);
                    } 
                }
    
                break;
    
            case 'retiros':

                $nombreCarpeta = "Retiros";

                $field_planilla_aportes = [
                    'tempFile' => tempFile('rg-field-planilla-aportes', 'PA'),
                    'field' => 'Planilla aportes'
                ];

                $field_poliza = [
                    'tempFile' => tempFile('rg-field-poliza', 'POL'),
                    'field' => 'Poliza'
                ];

                $field_rtm = [
                    'tempFile' => tempFile('rg-field-rtm', 'RTM'),
                    'field' => 'RTM'
                ];

                $field_soat = [
                    'tempFile' => tempFile('rg-field-soat', 'SOAT'),
                    'field' => 'SOAT'
                ];
    
                $campos_retiros = array($field_planilla_aportes, $field_poliza, $field_rtm, $field_soat);

                foreach ($campos_retiros as $campo_retiros) {
                    if($campo_retiros['tempFile'] && $campo_retiros['field']){
                        array_push($archivoSubido, $campo_retiros['field']);
                        uploadFile($campo_retiros['tempFile'], "Retiros/" . $nombreSubasta . "/" . $nombreArchivo);
                    } 
                }
                
                break;
            default:
                # code...
                break;
        }

        $archivoSubidoStr = implode(', ', $archivoSubido);

        $etapa;
        $etapaMsje;

        if($tiposArchivos == "registro-act"){
            $etapa = "Etapa: " . $nombreCarpeta  . " Actualización" . "\n";
            $etapaMsje = $nombreCarpeta  . " Actualización";
        } else {
            $etapa = "Etapa: " . $nombreCarpeta  . "\n";
            $etapaMsje = $nombreCarpeta;
        }



        $to = EMAIL_ADMIN;
        $title = $idRadicado . " App Superbid";
        $msje = "Nueva actividad desde " . URL_SITE . "\n"  . "\n";
        $msje .= "DATOS DE SUBIDA" . "\n";
        $msje .= $etapa;
        $msje .= "Documento cargado: " . $archivoSubidoStr  . "\n";
        $msje .= "Fecha: " . date("Y-m-d") . "\n";
        $msje .= "Hora: " . date("H:i:s") . "\n";
        $msje .= "Actividad exitosa" . "\n";
        $msje .= "ID Radicado: " . $idRadicado . "\n" ;
        // $msje .= "Política de privacidad: " . $GLOBALS['privacy_policies'];
        $headers = 'From: noreply@superbidcolombia.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($to, $title, $msje, $headers);

        header('Location:'.URL_SITE.'/gracias.php?rad='. $idRadicado . '&t-doc=' . $tipoDocumento . '&n-doc=' . $numDocumento . '&etapa=' . $etapaMsje . '&archivo=' . $archivoSubidoStr);
    } else {

        $to = EMAIL_ADMIN;
        $title = $idRadicado . " App Superbid";
        $msje = "Nueva actividad desde " . URL_SITE . "\n"  . "\n";
        $msje .= "Fecha: " . date("Y-m-d") . "\n" . "\n";
        $msje .= "Hora: " . date("H:i:s") . "\n" . "\n";
        $msje .= "Actividad fallida" . "\n" . "\n";
        $msje .= "ID Radicado: " . $idRadicado . "\n" . "\n";
        $headers = 'From: noreply@superbidcolombia.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($to, $title, $msje, $headers);

        header('Location:'.URL_SITE.'/error.php?rad='. $idRadicado . "&error=Debe diligenciar un número de documento");
    }
    
}
