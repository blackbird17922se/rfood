<?php

include '../models/caja.php';
include_once '../models/conexion.php';

$caja = new Caja();

switch ($_POST['funcion']) {

    case 'listarPedidosCaja':
        /* cajas con entrega pendiente */
        $caja->listarPedidosCaja();
        $json=array();
     
        
        foreach($caja->objetos as $objeto){
       


            $json[]=array(
                'idPedido'=>$objeto->id_pedido,
                'idMesa'=>$objeto->id_mesa,
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;

    case 'cargarDatosPedido':
        $idPedido = $_POST['ID'];
        $idMesa = $_POST['IDMESA'];
        /* pedidos con entrega pendiente */
        $caja->cargarDatosPedido($idPedido);
        $json=array();
        $jsonC=array();
        $jsonP=array();

        $nomProduct = "";
        $nomPresent = "";
        $cantidad = "";
        $precio = "";
        // $total="";
        
        foreach($caja->objetos as $objeto){
            $jsonP=[];
            $jsonC=[];
            $idDetProd = $objeto->id_det_prod;

            /* Consultar nombre platillo */
            $caja->ConsultarNomProducts($idDetProd);
            foreach($caja->objetos as $objn){

                $jsonP[]=array(
                    $nomProduct = $objn->nom,
                    $nomPresent = $objn->presnom,
                    $cantidad = $objeto->det_cant,
                    $precio = $objn->precio,
                
                );
            }
                
            $subtotal = $precio*$cantidad;

            // $total = $total + $subtotal;
        
            /* prueba  */
            $template = "       
                <tr prodId='$objeto->id_det_pedido' prodPrecio=''>
                    <td>$idMesa</td>
                    <td>$nomProduct</td>
                    <td>$nomPresent</td>
                    <td>$cantidad</td>
                    <td>$precio</td>
                    <td>$subtotal</td>
                    
                </tr>
            ";
            /* fin */
            

            $json[]=array(
                'idPedido' => $objeto->id_det_pedido,
                'idMesa'=>$idMesa,
                'prods'=> $jsonP,
                'template' => $template,
                'subtotal' => $subtotal
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;


    case 'registrarVenta':
        session_start();
        
        $total = $_POST['total'];
        $idOrd = $_POST['idOrdSel'];

        $idPedido = $idOrd;
        $idMesero = "";
        $idCocineroLider = "";
        $vendedor = $_SESSION['usuario'];

        /* Cargar id mesero y cocinero */
        $caja->cargarMeseroCocinero($idPedido);

        foreach($caja->objetos as $objId){
            $idMesero= $objId->id_mesero;
            $idCocineroLider= $objId->id_coc_lider;
        }        


        /* integra sfarma */
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d H:i:s');
        $caja->crearVenta($total,$fecha, $vendedor, $idMesero, $idCocineroLider);

        /* obtener id de la venta */
        $caja->ultimaVenta();
        foreach($caja->objetos as $objeto){
            $idVenta = $objeto->ultima_venta;
            // echo $idVenta;
        }

        /* +++ */

        try {
            $db = new Conexion();
            $conexion = $db->pdo;
            $conexion->beginTransaction();

            $caja->cargarDatosPedido($idPedido);        
            
            foreach($caja->objetos as $obj){

                $cantidad = $obj->det_cant;
                $cantidad2 = $obj->det_cant;
                $idProd = $obj->id_det_prod;

                /* consultar precio prod */
                
                while ($cantidad != 0) {
                    
                    $caja->agregarDetVenta($cantidad, $idProd, $idVenta);
                    
                    // echo 'add';
                    // echo "-cant: ".$cantidad;
                    // echo "-idprod: ".$obj->id_det_prod;
                    // echo "-idVenta: ".$idVenta;
                    $cantidad = 0;
                }

                $precio=0;
                // $caja->consultarDatosProducto($idProd);
                $caja->consultarPrecio($idProd);
                foreach($caja->objetos as $objPr){
                    $precio = $objPr->precio;           
                }
                $subtotal = $cantidad2 * $precio;
                $caja->insertRegVenta($precio, $cantidad2, $subtotal, $idProd, $idVenta);
                /* venta_prod(precio,cant,subtotal,prod_id_prod,venta_id_venta)  */




            }

            $conexion->commit();

        } catch (Exception $error) {
            $conexion->rollBack();
            // $caja->borrar($idVenta);
            echo $error->getMessage();
        }




    break;

    
    default:
        # code...
        break;
}