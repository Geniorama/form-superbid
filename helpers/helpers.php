<?php

function tempFile($file, $name){
    $document = $_FILES[$file]['tmp_name'];
    if ($document) {
        $ext = explode(".",$_FILES[$file]['name']);
        $ext = end($ext);

        $name_document = "/". $name . "." . $ext;

        return array("document" => $document, "ext" => $ext, "name_document" => $name_document);
    }
}