<?php
include '../models/mesa.php';
$mesa = new Mesa();

switch ($_POST['funcion']) {

    /* crear */
    case 1:
        $nom = $_POST['nom_mesa'];
        $mesa->crear($nom);
    break;

    /* read */
    case 2:
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
    break;

    /* Update */
    case 3:
        $nom_mesa = $_POST['nom_mesa'];
        $id_editado = $_POST['id_editado'];
        $mesa->editar($nom_mesa,$id_editado);
    break;

    /* Delete */
    case 4:
        $id_mesa = $_POST['ID'];
        $mesa->borrar($id_mesa);
    break;

    /* Listar en select list */
    /* Solo lista mesas que no tengan ordenes ya hechas */
    case 5:
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
    break;
    
    default:
        # code...
        break;
}