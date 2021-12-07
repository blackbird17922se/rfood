<?php
include '../models/itemModel.php';

$items = new ItemModel();
$json = array();

switch ($_POST['funcion']) {
    
    case 150:

        $idItem = $_POST['ITEM_ID'];

        $items->listarDatosItem($idItem);

        foreach($items->objetos as $objeto){
            $json[] = array(                

                'codbar'  => $objeto->codbar,
                'nombre'    => $objeto->nombre,
                'iva'     => $objeto->iva,
                'precio'   => $objeto->precio,
            );
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;


    case 151:

        $idItem = $_POST['idItem'];

        $items->listarIngredsItem($idItem);

        foreach($items->objetos as $objeto){
            $json[]=array(
                'idIngred'=>$objeto->id_it_menu_ingr,
                'nomIngred'=>$objeto->nom_ingr,
                'medida'=>$objeto->medida_ingr,
                'cantidad'=>$objeto->cant_ingr
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;


    case 160:

        $idItemMenu   = $_POST['idItemMenu'];
        $nIngredsItem = json_decode($_POST['json']);

        foreach ($nIngredsItem as $ingred) {
            $idIngred  = $ingred->id_prod;
            $nomIngred = $ingred->nombre;
            $medida    = $ingred->medida;
            $cantidad  = $ingred->cantidad;
    
    
            $items->agregarNIngredItem($idItemMenu, $idIngred, $nomIngred, $medida, $cantidad);
        }
    
        // $respCrearItemMenu = $items->agregarNIngredItem($codbar, $cat_item, $nombreItem, $pres_item, $precio, $iva);
    

    break;


    // Editar Cantidad del Ingrediente del Item
    case 161:
        $ingred_cant = $_POST['ingred_cant'];
        $id_editado = $_POST['id_editado'];
        $items->editarCantIngredItem($ingred_cant,$id_editado);
    break;


    // Borrar
    case 162:
        $id_ingred = $_POST['ID'];
        $items->borrarIngredItem($id_ingred);
    break;
    
    
    default:
        # code...
        break;
}