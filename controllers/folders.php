<?php

require_once "./../connect/connect.php";

$database = new ConnectDropbox();
$dropbox = $database->connectDr();

class Folder {
    function obtenerCarpetas($carpeta){
        global $dropbox;
        $listFolderContents = $dropbox->listFolder("/" . $carpeta);

        $items = $listFolderContents->getItems();
        $cursor = $listFolderContents->getCursor();
        
        $arrItems = [];

        foreach($items as $item){
            array_push($arrItems, ["nameFolder" => $item->getName()]);
        }

        //If more items are available
        $hasMoreItems = $listFolderContents->hasMoreItems();

        //If more items are available
        if ($hasMoreItems) {
            //Fetch Cusrsor for listFolderContinue()
            $cursor = $listFolderContents->getCursor();

            //Paginate through the remaining items
            $listFolderContinue = $dropbox->listFolderContinue($cursor);

            $remainingItems = $listFolderContinue->getItems();

            foreach ($remainingItems as $remItem) {
                array_push($arrItems, ["nameFolder" => $remItem->getName()]);
            }

            sort($arrItems);
            return $arrItems;
        }

        sort($arrItems);
        return $arrItems;
    }
}



