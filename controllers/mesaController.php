<?php
include '../models/mesa.php';
$mesa = new Mesa();

if($_POST['funcion']=='crear'){
    $nom = $_POST['nom_mesa'];
    $mesa->crear($nom);
}

if($_POST['funcion']=='editar'){
    $nom_mesa = $_POST['nom_mesa'];
    $id_editado = $_POST['id_editado'];
    $mesa->editar($nom_mesa,$id_editado);
}

if($_POST['funcion']=='buscar'){
    $mesa->buscar();
    $json=array();
    foreach($mesa->objetos as $objeto){
        $json[]=array(
            'id_mesa'=>$objeto->id_mesa,
            'nom'=>$objeto->nom
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

if($_POST['funcion'] =='borrar'){
    /* OJO: $_POST['ID'] viene desde mesaratorio.js en la const ID = $(ELEM).attr('mesaId'); */
    $id_mesa = $_POST['ID'];
    $mesa->borrar($id_mesa);
}

if($_POST['funcion'] =='listarMesas'){
    $mesa->listarMesas();
    $json=array();
    foreach($mesa->objetos as $objeto){
        $json[]=array(
            'id_mesa'=>$objeto->id_mesa,
            /* OJO: ...=> $objeto->NOMBRE DEL CAMPO EL LA BD */
            'nom_mesa'=>$objeto->nom
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}