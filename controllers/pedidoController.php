<?php

include '../models/pedido.php';
include_once '../models/conexion.php';

$pedido = new Pedido();

switch ($_POST['funcion']) {
    
    /* CREATE */
    case 1:
        session_start();
        $id_mesa   = $_POST['id_mesa'];
        $observ = $_POST['observ'];
        $entregado = $_POST['entregado'];
        $terminado = $_POST['terminado'];
        $pagado    = $_POST['pagado'];
        $id_mesero = $_SESSION['usuario'];
        $productos = json_decode($_POST['json']);
        $fecha = date('Y-m-d H:i:s');
        
        $pedido->nuevoPedido($id_mesa, $id_mesero, $observ, $entregado,$terminado,$pagado);

        /* obtener id de la venta */
        $pedido->ultimoPedido();
        foreach($pedido->objetos as $objeto){
            $idPedido = $objeto->ultimo_pedido;
            echo $idPedido;
        }

        try {
            $db = new Conexion();
            $conexion = $db->pdo;
            $conexion->beginTransaction();
            /* Recorrer todos losproductos...  ->cantidad es traida desde el localStorage */
            foreach ($productos as $prod) {
                $cantidad = $prod->cantidad;

                while($cantidad != 0){
                    $sql = "INSERT INTO det_pedido(det_cant,id_det_prod,id_det_pedido) 
                        VALUES(
                            '$cantidad',
                            '$prod->id_prod',
                            '$idPedido'      
                        )
                    ";
                    $conexion->exec($sql);
                    $cantidad = 0;
                }
            }
            $conexion->commit();

        }catch (Exception $error) {
            $conexion->rollBack();
            $venta->borrar($idVenta);
        echo $error->getMessage();
        }

    break;


    /* listarPedidos */
    case 2:
        /* pedidos con entrega pendiente */
        $pedido->listarPedidosPendEntrega();
        $json=array();
        $jsonP=array();

        foreach($pedido->objetos as $objeto){
            $jsonP=[];

            $pedido->listarProdPedido($objeto->id_pedido);
            foreach($pedido->objetos as $objP){
                
                $jsonP[]=array(
                    $objP->nombprod,
                    $objP->presnom,
                    $objP->det_cant,
                );
            }

            $json[]=array(
                'idPedido' => $objeto->id_pedido,
                'nomMesa'=>$objeto->nom_mesa,
                'prods'=> $jsonP
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;


    /* read mesa y pedidos */
    case 3:
        $pedido->listarMesas();
        $json=array();
        $jsonP=array();

        foreach($pedido->objetos as $objeto){
            $jsonP=[];

            $pedido->listarPedidoMesa($objeto->id_mesa);
            foreach($pedido->objetos as $objP){
                
                $jsonP[]=array(
                    $objP->nombprod,
                    $objP->presnom,
                    $objP->det_cant,
                );
            }

            $json[]=array(
                'id_mesa'=>$objeto->id_mesa,
                'nom'=>$objeto->nom,
                'prods'=> $jsonP
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;


    /* listarPedTerminados pedidos con entrega pendiente */
    case 5:
        $pedido->listarPedTerminados();
        $json=array();
        $jsonP=array();
        
        foreach($pedido->objetos as $objeto){
            $jsonP=[];

            $pedido->listarProdPedido($objeto->id_pedido);
            foreach($pedido->objetos as $objP){
                
                $jsonP[]=array(
                    $objP->nom,
                    $objP->det_cant,
                    $objP->presnom,
                );
            }

            $json[]=array(
                'idPedido' => $objeto->id_pedido,
                'nomMesa'=>$objeto->nom_mesa,
                'prods'=> $jsonP
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;

    case 'terminado':
        session_start();
        $id_coc_lider = $_SESSION['usuario'];

        $idOrden = $_POST['ID'];
        $pedido->cambiarEstTerminado($idOrden, $id_coc_lider);
    break;

    case 'entregado':
        $idOrden = $_POST['ID'];
        $pedido->cambiarEstEntregado($idOrden);
    break;


    /* Cambiar estado de la orden a Pagado */
    case 9:
        session_start();
        $pedido->cambiarEstPagado($_POST['idOrdenSel'], $_SESSION['usuario']);
    break;

    /* Bloquear Mesa */
    case 10:
        $pedido->bloquearMesa($_POST['mesa']);
    break;

    /* Desbloquear Mesa */
    case 11:
        $pedido->desBloquearMesa($_POST['mesa']);
    break;

    /* Cargar los items de esa orden (Util en editar pedido) */
    case 12:
        $json=array();
        $pedido->listarProdPedido($_POST['ID_MESA']);
        foreach($pedido->objetos as $objeto){
            
            $json[]=array(
                'idprod'=>$objeto->id_det_prod,
                'nombprod'=>$objeto->nombprod,
                'cantidad'=>$objeto->det_cant,
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;

    /* Editar la cantidad del Item (Util en editar pedido)*/
    case 13:
        $pedido->editarCantItem($_POST['ID_ORDEN'],$_POST['idItem'],$_POST['itemCant']);
    break;


    // agregar los nuevos Items a la orden
    case 14:

        $idOrden   = $_POST['ID_ORDEN'];
        $newItems = json_decode($_POST['json']);

        foreach ($newItems as $newItem) {
            $idItem  = $newItem->id_prod;
            $pedido->agregarNewItem($idOrden, $newItem->id_prod, $newItem->cantidad);
        }
    break;


    // Borrar Item de la Orden
    case 15:
        $pedido->borrarItemOrden($_POST['ID']);
    break;


    /* BUILD 3.0  
        Donde se solicita que pase de la seccion de pedidos a caja
    */
    case 16:
        session_start();
        $id_coc_lider = $_SESSION['usuario'];
        $idOrden = $_POST['ID'];
        
        $pedido->cambiarEstTerminado($idOrden, $id_coc_lider);
        $pedido->cambiarEstEntregado($idOrden);

    break;


    /* Listar pedido de esa mesa */
    case 17:
        /* pedidos con entrega pendiente */
        $pedido->listarPedidoMesa($_POST['idMesa']);
        $json=array();
        $jsonP=array();

        foreach($pedido->objetos as $objeto){
            $jsonP=[];

            $pedido->listarProdPedido($objeto->id_pedido);
            foreach($pedido->objetos as $objP){
                
                $jsonP[]=array(
                    $objP->nombprod,
                    $objP->presnom,
                    $objP->det_cant,
                );
            }

            $json[]=array(
                'idPedido' => $objeto->id_pedido,
                'nomMesa'=>$objeto->nom_mesa,
                'prods'=> $jsonP
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;

    
    /* Lista las mesas y la orden q tenga */
    case 18:
        $pedido->listarMesas();
        $json=array();
        $jsonP=array();
        $idPedido;

        foreach($pedido->objetos as $objeto){
            $objeto->nom;   //nombre mesa
            $jsonP=[];

            $pedido->listarOrdenMesa($objeto->id_mesa);
            foreach($pedido->objetos as $objP){
                
                $jsonP[]=array(
                    $objP->id_pedido,
                );
            }

            $json[]=array(
                'id_mesa'=>$objeto->id_mesa,
                'nomMesa'=>$objeto->nom,
                'prods'=> $jsonP
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;


    /* Cargar los items de esa orden (Util en editar pedido) */
    case 19:
        $json=array();
        $pedido->listarItemsOrden($_POST['ID_ORDEN']);
        foreach($pedido->objetos as $objeto){
            
            $json[]=array(
                'idprod'=>$objeto->id_det_prod,
                'nombprod'=>$objeto->nombprod,
                'cantidad'=>$objeto->det_cant,
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;

    /* Verificar items repetidos */
    case 20:
        
        $idOrden   = $_POST['ID_ORDEN'];
        $newItems = json_decode($_POST['json']);
        $response     = 0;

        foreach ($newItems as $newItem) {
            $idItem  = $newItem->id_prod;

            if($pedido->verificarItemRepetido($newItem->id_prod,$idOrden)){
                $response = 1;
            }
        }
        echo $response;

    break;

    case 21:
        $pedido->listarProdPedido($_POST['idOrden']);
        $json=array();
        foreach ($pedido->objetos as $objeto) {
            $json[]=$objeto;
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;

    case 22:
        $pedido->cargarObservOrden($_POST['idOrden']);
        $json=array();
        foreach ($pedido->objetos as $objeto) {
            $json[]=$objeto;
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;

    default:
    break;
}



/* case 14:

    $idOrden   = $_POST['ID_ORDEN'];
    $newItems = json_decode($_POST['json']);
    $response     = false;
    $nomIngredGl  = null;
    $flag         = false;
    $insert       = false;

    foreach ($newItems as $newItem) {
        $idItem  = $newItem->id_prod;

        if($pedido->verificarItemRepetido($newItem->id_prod,$idOrden)){
            $response    = true;
        }else{
            $pedido->agregarNewItem($idOrden, $newItem->id_prod, $newItem->cantidad);
            echo 'add';
        }
    }
break; */