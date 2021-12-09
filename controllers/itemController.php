<?php
include '../models/itemModel.php';

$items = new ItemModel();
$json = array();

switch ($_POST['funcion']) {

    // Listar los items
    case 140:
        $items->listarItems();
        $json=array();
        foreach($items->objetos as $objeto){

            $json[]=array(
                /* '' =>$objeto->ALIAS ASIGNADO */
                'id_prod'   =>$objeto->id_prod,
                'codbar'    =>$objeto->codbar,
                'nombre'    =>$objeto->nombre,
                'prod_tipo' =>$objeto->prod_tipo,
                'prod_pres' =>$objeto->prod_pres,
                'precio'    =>$objeto->precio,
                'iva'       =>$objeto->iva,    

                /* Para cargar los nombres en lugar de los id */
                'tipo'         =>$objeto->tipo,
                'presentacion' =>$objeto->presentacion
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;


    // Crear
    case 145:
        $codbar     = $_POST['codbar'];
        $cat_item   = $_POST['cat_item'];
        $nombreItem = $_POST['nombre'];
        $pres_item  = $_POST['pres_item'];
        $precio     = $_POST['precio'];
        $iva        = $_POST['iva'];
        $idNuevoItem= 0;

        $ingreds    = json_decode($_POST['json']);

        $respCrearItemMenu = $items->crearItem($codbar, $cat_item, $nombreItem, $pres_item, $precio, $iva);

        if($respCrearItemMenu){

            /* obtener ultimo Item registrado */
            $items->cargarUltimoItemReg();
            foreach($items->objetos as $objeto){
                $idNuevoItem = $objeto->ultimoreg;
                // echo $idProduct;
            }
            
            foreach ($ingreds as $ingred) {
                $idIngred  = $ingred->id_prod;
                $nomIngred = $ingred->nombre;
                $medida    = $ingred->medida;
                $cantidad  = $ingred->cantidad;
        
                $items->agregarNIngredItem($idNuevoItem, $idIngred, $nomIngred, $medida, $cantidad);
            }
            echo "addItem";

        }else{
            echo "errorAddItem";
        }
    break;


    // Editar
    case 146:
        /* datos recibidos desde producto.js >>> $.post('../controllers/productoController.php',{fu... */
        $id        = $_POST['id'];
        $nombre    = $_POST['nombre'];
        $iva       = $_POST['iva'];
        $precio    = $_POST['precio'];
        $prod_tipo = $_POST['prod_tipo'];
        $prod_pres = $_POST['prod_pres'];

        $items->editarItem($id,$nombre,$prod_tipo,$prod_pres,$precio,$iva);
    break;


    //Borrar
    case 147:
        $id = $_POST['id'];
        $items->borrarItem($id);
    break;
    

    // Listar datos del item
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


    // Listar los ingredientes actuales del item
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


    // agregar el nuevo ingrediente para el item
    case 160:

        $idItemMenu   = $_POST['idItemMenu'];
        $nIngredsItem = json_decode($_POST['json']);
        $response     = false;
        $nomIngredGl  = null;
        $flag         = false;
        $insert       = false;

        foreach ($nIngredsItem as $ingred) {
            $idIngred  = $ingred->id_prod;
            $nomIngred = $ingred->nombre;

            if($items->verificarIngredRepetido($idIngred,$idItemMenu)){
                $response    = true;
                $nomIngredGl = $nomIngred;
            }
        }

        if($response){
            echo $nomIngredGl;
        }else{
            foreach ($nIngredsItem as $ingred) {
                $idIngred  = $ingred->id_prod;
                $nomIngred = $ingred->nombre;
                $medida    = $ingred->medida;
                $cantidad  = $ingred->cantidad;

                $items->agregarNIngredItem($idItemMenu, $idIngred, $nomIngred, $medida, $cantidad);
            }
            echo 'add';
        }
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