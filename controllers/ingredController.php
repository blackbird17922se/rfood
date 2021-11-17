<?php
require_once('../vendor/autoload.php');
include '../models/ingredModel.php';
include_once '../models/conexion.php';

$product = new IngredModel();

/* MOSTRAR PRODUCTOS */
if($_POST['funcion'] == 'listarIngreds'){
    $product->listarIngreds();
    $json=array();
    foreach($product->objetos as $objeto){
        /* Funcion que busca en los lotes, los productos con id X, a medida que los va sumando, suma su cantidad */
        $product->obtenerStock($objeto->id_inv_prod);
        foreach($product->objetos as $obj){
            /* $obj->total: el total viene del alias total en el modelo >> "SELECT SUM(stock) as >>total<< ...*/
            $total = $obj->total;
        }

        $json[]=array(
            /* '' =>$objeto->ALIAS ASIGNADO */
            'id_inv_prod' => $objeto->id_inv_prod,
            'codbar'      => $objeto->codbar,
            'nombre'      => $objeto->nombre,
            'stock'       => $total,

            // carga los id
            'prod_tipo'   => $objeto->prod_tipo,
            'un_medida'   => $objeto->un_medida,

            // Para cargar los nombres en lugar de los id    
            'tipo'        => $objeto->tipo,
            'medida'      => $objeto->medida
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}


/* CREAR */
if($_POST['funcion']=='crear'){
    /* datos recibidos desde producto.js >>> $.post('../controllers/productoController.php',{fu... */
    $codbar    = $_POST['codbar'];
    $nombre    = $_POST['nombre'];
    $prod_tipo = $_POST['prod_tipo'];
    $medida    = $_POST['un_medida'];
    $product->crear($codbar,$nombre,$prod_tipo,$medida);
}


if($_POST['funcion'] == 'ultimoReg'){

    $db = new Conexion();
    $conexion = $db->pdo;
    $sql="SELECT MAX(id_inv_prod) AS ultimo_prod FROM ingred";
    $query = $conexion->prepare($sql);
    $query->execute();
    $afg=$query->fetchall();
    $jsonstring = json_encode($afg);
    echo $jsonstring;

}


/* **************************** EDITAR **************************** */
if($_POST['funcion'] =='editar'){

    $id        = $_POST['id'];
    $nombre    = $_POST['nombre'];
    $prod_tipo = $_POST['prod_tipo'];
    $medida    = $_POST['un_medida'];
    
    $product->editar($id,$nombre,$prod_tipo,$medida);

}

if($_POST['funcion'] =='borrar'){
    /* OJO: $_POST['ID'] viene desde labratorio.js en la const ID = $(ELEM).attr('labId'); */
    $id = $_POST['id'];
    $product->borrar($id);
}

/* para cuando se actualiza un precio o el stock del producto, 
la ctualizacion se mostrada en tiempo real (por ejemplo en e carr de compras) */
if($_POST['funcion'] == 'buscar_id'){
    /* post recibido desde carrito.js : funcion recuperarLS_car ... $.post('../controllers/productoController.php',{funcion,id_inv_producto},(response) */
    $id=$_POST['idIngred'];

    $product->buscar_id($id);
    $json=array();
    foreach($product->objetos as $objeto){
        /* Funcion que busca en los lotes, los productos con id X, a medida que los va sumando, suma su cantidad */
        $product->obtenerStock($objeto->id_inv_prod);
        foreach($product->objetos as $obj){
            /* $obj->total: el total viene del alias total en el modelo >> "SELECT SUM(stock) as >>total<< ...*/
            $total = $obj->total;
        }

        $json[]=array(
            /* '' =>$objeto->ALIAS ASIGNADO */
            'id_inv_prod'=>$objeto->id_inv_prod,
            'nombre'=>$objeto->nombre,           
            'stock'=>$total,
            
            'tipo'=>$objeto->tipo,
            'medida'=>$objeto->medida,
            'tipo_id'=>$objeto->prod_tipo,
            'pres_id'=>$objeto->un_medida
        );
    }

    /* los corchetes y elcero es porque se le van a enviar los valores UNO POR UNO */
    // $jsonstring = json_encode($json[0]);
    // echo $jsonstring;

    $jsonstring = json_encode($json);
    echo $jsonstring;
}

if($_POST['funcion']=='verificar-stock'){
    $error =0;
    $productos = json_decode($_POST['productos']);

    foreach($productos as $objeto){
        /* Funcion que busca en los lotes, los productos con id X, a medida que los va sumando, suma su cantidad */
        $product->obtenerStock($objeto->id_inv_prod);
        foreach($product->objetos as $obj){
            $total = $obj->total;
        }
        if($total>=$objeto->cantidad && $objeto->cantidad>0){
            /* si hay stock, sume 0 */
            $error=$error+0;
        }else{
            $error=$error+1;
        }
    }
    echo $error;
}


/* Traer productos */
// if($_POST['funcion']=='traer_productos'){
//     // echo "here";
//     $html = "";

//     /*
//     * recibir la variable productos enviada desde carrito js en async function recuperarLS_car_compra():
//     * body: 'funcion='+funcion+'&&productos='+JSON.stringify(productos)
//     * Decodificar el stryngify enviado
//     */
//     $productos = json_decode($_POST['productos']);

//     /* 
//      * Recorrer la variable productos
//      * Un $resultado es un producto de toda la lista
//     */
//     foreach($productos as $resultado){
//         $product->buscar_id($resultado->id_inv_prod);
//         // var_dump($product);

//         /* Acceder a todos los datos de ese producto al acceder a ->objetos*/
//         foreach($product->objetos as $objeto){

//             /* Calcular subtotal */
//             $subtotal= $objeto->precio * $resultado->cantidad;

//             /* Calcular el IVA de un producto */
//             $iva = 0.19;            /* Variable IVA */
//             $divIva = 1.19;            /* Variable Para restar el iva de un total */
//             $i = 0;
            
//             /* Restarle el iva del precio establecido del producto
//             Mostrara al final el precio base (sin iva) */
//             if($objeto->iva == 1){
//                 $sinIva = $objeto->precio / $divIva;
//                 $sinIva = round($sinIva,0);  /* Redondear la cifra */
    
//                 /* Calcular el iva del producto */
//                 $ivaProd = $sinIva * $iva;
//                 $ivaProd = round($ivaProd,0);

//             }else{
//                 $sinIva = $objeto->precio;
//                 $ivaProd = 0;
//             }


//             /* Obtener el stock */
//             $product->obtenerStock($objeto->id_inv_prod);
//             foreach($product->objetos as $obj){
//                 $stock = $obj->total;
//             }

//             $html.="
//             <tr prodId='$objeto->id_inv_prod' prodPrecio='$objeto->precio'>
//                 <td>$objeto->nombre</td>
//                 <td>$stock</td>
//                 <td class='precio'>$objeto->precio</td>
//                 <td>$objeto->compos</td>
//                 <td>$sinIva</td>
//                 <td>$ivaProd</td>
//                 <td>$objeto->laboratorio</td>
//                 <td>$objeto->medida</td>
//                 <td>
//                     <input type='text' min='1' class='form-control cant_producto' value='$resultado->cantidad'>
//                 </td>
//                 <td class='subtotales'>
//                     <h5>$subtotal</h5>
//                 </td>
//                 <td><button class='btn btn-danger borrar-producto' ><i class='fas fa-times-circle'></i></button></td>
//             </tr>
//             ";
//         }
//     }
//     echo $html;
// }


/* REPORTES EN PDF */
if($_POST['funcion']=='rep_prod'){
    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d H:i:s');
    // $html = getHtml($id_venta);
    $html = '

        <header>
            <h1>Reporte productos</h1>
            <div id="project">
                <div><span>Fecha: </span>'.$fecha.'</div>
            </div>
        </header>
        <table>
            <thead>
                <tr>
                    <th>N</th>
                    <th>Producto</th>
                    <th>Concentracion</th>
                    <th>Adicional</th>
                    <th>Laboratorio</th>
                    <th>medida</th>
                    <th>Tipo</th>
                    <th>Stock</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
    ';



    $product->reporteProductos();
    $contador = 0;
    foreach ($product->objetos as $objeto) {
        $contador++;

        $product->obtenerStock($objeto->id_inv_prod);
        foreach($product->objetos as $obj){
            $stock = $obj->total;
        }
    
        $html.='
            <tr>
                <th class="service">'.$contador.'</th>            
                <th class="service">'.$objeto->nombre.'</th>
                <th class="service">'.$objeto->compos.'</th>
                <th class="service">'.$objeto->adici.'</th>
                <th class="service">'.$objeto->laboratorio.'</th>
                <th class="service">'.$objeto->medida.'</th>
                <th class="service">'.$objeto->tipo.'</th>
                <th class="service">'.$stock.'</th>
                <th class="service">'.$objeto->precio.'</th>
            </tr>
        ';
        
    }
    $html.='
            </tbody>
        </table>
    ';
    // $css = file_get_contents("../css/pdf.css");
    $mpdf = new \Mpdf\Mpdf();
    // $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [50, 800]]);
    // $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->Output("../pdf/pdf-".$_POST['funcion'].".pdf","F");
}





/************************** CODBAR ********************************* */
// if($_POST['funcion']=='buscaCodbar'){
//     $consulta = $_POST['consulta'];
//     $product->buscarCodbarModel($consulta);
//     $json=array();
//     foreach($product->objetos as $objeto){
//         /* Funcion que busca en los lotes, los productos con id X, a medida que los va sumando, suma su cantidad */
//         $product->obtenerStock($objeto->id_inv_prod);
//         foreach($product->objetos as $obj){
//             /* $obj->total: el total viene del alias total en el modelo >> "SELECT SUM(stock) as >>total<< ...*/
//             $total = $obj->total;
//         }

//         $json[]=array(
//             /* '' =>$objeto->ALIAS ASIGNADO */
//             'id_inv_prod'=>$objeto->id_inv_prod,
//             'nombre'=>$objeto->nombre,
            
//             'stock'=>$total,
//             'lab_id'=>$objeto->prod_lab,
//             'tipo_id'=>$objeto->prod_tipo,
//             'pres_id'=>$objeto->un_medida
//         );
//     }
//     $jsonstring = json_encode($json);
//     echo $jsonstring;
// }