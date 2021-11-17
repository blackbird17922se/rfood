<?php
include '../models/invMedida.php';
$medida = new InvMedida();

switch ($_POST['funcion']) {
    case 'consultarmedidas':

        $medida->consultarmedidas();
        $json=array();
        foreach($medida->objetos as $objeto){
            $json[]=array(
                'id_medida'=>$objeto->id_medida,
                'nom'=>$objeto->nom
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
        
    break;

    case 'crear':
        $nom = $_POST['nom_medida'];
        $medida->crearmedida($nom);
        
    break;

    case 'editar':
        $nom_medida = $_POST['nom_medida'];
        $id_editado = $_POST['id_editado'];
        $medida->editarmedida($nom_medida,$id_editado);
        
    break;


    case 'borrar':
        /* OJO: $_POST['ID'] viene desde medidaratorio.js en la const ID = $(ELEM).attr('medidaId'); */
        $id_medida = $_POST['ID'];
        $medida->borrarmedida($id_medida);
        
    break;

    case 'listarMedidas':
        $medida->listarInvmedidas();
        $json=array();
        foreach($medida->objetos as $objeto){
            $json[]=array(
                'id_medida'=>$objeto->id_medida,
                'nom_medida'=>$objeto->nom
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;


    case 'consultarDatosMedida':
        $idMedida = $_POST['un_medida'];

        $medida->consultarDatosMedida($idMedida);

        foreach($medida->objetos as $objeto){
            $json[]=array(
                'idMedida'=>$objeto->id_medida,
                'nomMedida'=>$objeto->nom
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;

    
    default:
        echo "ERROR, OPCION INVALIDA";
    break;
}