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
        $formaPago = $_POST['formaPago'];

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
        $caja->crearVenta($total, $formaPago, $fecha, $vendedor, $idMesero, $idCocineroLider, $idOrd);

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

                $cantidad = $obj->det_cant; //Cantidad del plato solicitado
                $cantidad2 = $obj->det_cant;
                $idProd = $obj->id_det_prod;
                $totalIngCant = 0;
                echo "   ++var cant ini:   ++".$cantidad;



                $caja->cargarIngreds($idProd);
                foreach($caja->objetos as $ingred){
                    $ih = $ingred->id_ing_prod;
                    echo "----> cargarIngreds(): ";
                    echo "inged:". $ih;
                    echo "cant:". $ingred->cant;
                    echo "-------";
                    $ingCant = $ingred->cant;   //Cantidad del ingrediente

                    // echo $cantidad * $ingCant;
     
                $totalIngCant = $cantidad * $ingCant;
                echo "TotIngCant ".$totalIngCant;

                /* consultar precio prod */
                
                while ($totalIngCant != 0) {

             

                        /* Descontar productos */
                        /* seleccionael lote mas proximo a vencer */
                        $sql="SELECT * FROM inv_lote WHERE vencim = (SELECT MIN(vencim) FROM inv_lote WHERE lote_id_prod = :id) AND lote_id_prod = :id";
                        $query = $conexion->prepare($sql);
                        $query->execute(array(
                            ':id'=>$ingred->id_ing_prod
                        ));



                        $lote = $query->fetchall();
                        foreach ($lote as $lote) {
                            if($totalIngCant < $lote->stock){
                            
                                $conexion->exec("UPDATE inv_lote SET stock = stock - '$totalIngCant' WHERE id_lote = '$lote->id_lote'");
                                $totalIngCant = 0;
                            }

                            /* ingred->cant pedida es igual a la ingred->cant en el stock */
                            if($totalIngCant == $lote->stock){
                            
                                $conexion->exec("DELETE FROM inv_lote WHERE id_lote = '$lote->id_lote'");
                                $totalIngCant = 0;
                            }

                            /* Cuaando la ingred->cant pedida es superior a la ingred->cant del stock de un lote
                                y debe eliminar ese lote y consumir los productos del siguiente lote*/
                            if($totalIngCant > $lote->stock){
                                $conexion->exec("DELETE FROM inv_lote WHERE id_lote = '$lote->id_lote'");
                                $totalIngCant -= $lote->stock;
                                echo "   ++var cant 3caso:   ++".$totalIngCant;


                        

                            }
                        }

                   
                    
                    $caja->agregarDetVenta($cantidad, $idProd, $idVenta);
                    // $cantidad = 0;
                }
                
                /* pp */

            }   //fin cargIngr
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
            // echo "oK";

        } catch (Exception $error) {
            $conexion->rollBack();
            // $caja->borrar($idVenta);
            echo $error->getMessage();
        }




    break;

    /* PARA ACCEDER A LA ULTIMA VENTA */
    case 'ultimaVenta':
        $caja->ultimaVenta();
        foreach($caja->objetos as $objeto){
            $idVenta = $objeto->ultima_venta;
            $_SESSION['idUltimaVenta'] = $idVenta;
            echo $idVenta;
        }
    break;

    case 'verificarStock':
        $cantPedido = 0;
        $cantIngred = 0;
        $totalStock=0;
        $response = 0;
        $totalIngred = 0;

        $idOrdSel = $_POST['idOrdSel'];

        /* Consultar los detalles de ese pedido para conocer la cantidad */
        $caja->cargarDatosPedido($idOrdSel);

        foreach ($caja->objetos as $detalle){

            $cantPedido = $detalle->det_cant;

            /* Consultar ingredientes */
            $caja -> cargarIngreds($detalle->id_det_prod);

            foreach($caja->objetos as $ingred){

                $idIngProduct = $ingred->id_ing_prod;
 
                $cantIngred = $ingred->cant;

                /* multiplica la cantidad de materia prima que requiere ese plato por
                    la cantidad de platos solicitados 
                */
                $totalIngred = $cantIngred * $cantPedido;

                $caja->obtenerStock($idIngProduct);

                foreach($caja->objetos as $cantStock){
                    // echo ' Total en STOCK:'.$cantStock->total;
                    $totalStock = $cantStock->total;
                }

                /**
                 * Si la cantidad en el stock es mayor al total de ingredientes
                 * que se va a consumir por plato, retornar 0 para indicar que 
                 * hay stock, sino, sumara 1 por cada producto cuya materia prima
                 * esta escasa. 
                **/
                if($totalStock >= $totalIngred && $totalIngred > 0){
                    $response += 0;
                }else{
                    $response++;
                }
            }
            

        }
        echo $response;



    break;


    
    default:
        # code...
        break;
}