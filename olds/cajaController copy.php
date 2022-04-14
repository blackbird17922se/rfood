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
        /* funcion,total,idOrdSel */
        $total = $_POST['total'];
        $idOrd = $_POST['idOrdSel'];

        $idPedido = $idOrd;

        


        /* integra sfarma */
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d H:i:s');
        $caja->crearVenta($total,$fecha,$vendedor=1);


        /* +++ */

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

            $jsonstring = json_encode($jsonP);
            echo $jsonstring;


        }

        try {
            $db = new Conexion();
            $conexion = $db->pdo;
            $conexion->beginTransaction();
        } catch (\Throwable $th) {
            //throw $th;
        }




    break;

    
    default:
        # code...
        break;
}