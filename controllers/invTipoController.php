<?php
include '../models/invTipo.php';
$tipo = new InvTipo();

switch ($_POST['funcion']) {
    case 'consultarTipos':

        $tipo->consultarTipos();
        $json=array();
        foreach($tipo->objetos as $objeto){
            $json[]=array(
                'id_inv_tipo'=>$objeto->id_inv_tipo,
                'nom'=>$objeto->nom
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
        
    break;

    case 'crear':
        $nom = $_POST['nom_tipo'];
        $tipo->crearTipo($nom);
        
    break;

    case 'editar':
        $nom_tipo = $_POST['nom_tipo'];
        $id_editado = $_POST['id_editado'];
        $tipo->editarTipo($nom_tipo,$id_editado);
        
    break;


    case 'borrar':
        /* OJO: $_POST['ID'] viene desde tiporatorio.js en la const ID = $(ELEM).attr('tipoId'); */
        $id_tipo = $_POST['ID'];
        $tipo->borrarTipo($id_tipo);
        
    break;

    case 'listarInvTipos' || 'listarTipoIngred':
        $tipo->listarInvTipos();
        $json=array();
        foreach($tipo->objetos as $objeto){
            $json[]=array(
                'id_tipo'=>$objeto->id_inv_tipo,
                'nom_tipo'=>$objeto->nom
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;

    // case 'listarTipoIngred':
    //     $tipo->listarInvTipos();
    //     $json=array();
    //     foreach($tipo->objetos as $objeto){
    //         $json[]=array(
    //             'id_tipo'=>$objeto->id_inv_tipo,
    //             'nom_tipo'=>$objeto->nom
    //         );
    //     }
    //     $jsonstring = json_encode($json);
    //     echo $jsonstring;
    // break;

    
    default:
        echo "ERROR, OPCION INVALIDA";
    break;
}