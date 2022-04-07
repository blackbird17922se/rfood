<?php
include '../models/descuadreInv.php';
$descuadre = new DescuadreInv();

$funcion = $_POST['funcion'];

switch ($funcion) {
    case 1:
        $descuadre->cargarDescuadres();
        $json=array();
        foreach ($descuadre->objetos as $objeto) {
            $json['data'][]=$objeto;
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;

    case 4:
        $id = $_POST['id'];
        $descuadre->borrar($id);
    break;
    
    default:
        echo 66;
    break;
}