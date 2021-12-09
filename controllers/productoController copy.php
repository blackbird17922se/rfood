<?php
require_once('../vendor/autoload.php');
include '../models/producto.php';
include_once '../models/conexion.php';

$product = new Producto();

/* MOSTRAR PRODUCTOS */
// CONSULTAR ITEMCONTROLER 149
if($_POST['funcion'] == 'listarProducts'){
    $product->listarProducts();
    $json=array();
    foreach($product->objetos as $objeto){

        $json[]=array(
            /* '' =>$objeto->ALIAS ASIGNADO */
            'id_prod'=>$objeto->id_prod,
            'codbar'=>$objeto->codbar,
            'nombre'=>$objeto->nombre,
            'prod_tipo'=>$objeto->prod_tipo,
            'prod_pres'=>$objeto->prod_pres,
            'precio'=>$objeto->precio,
            // 'iva'=>$objeto->iva,    

            /* Para cargar los nombres en lugar de los id */
            'tipo'=>$objeto->tipo,
            'presentacion'=>$objeto->presentacion
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}


/* CREAR */
// if($_POST['funcion']=='crear'){
//     /* datos recibidos desde producto.js >>> $.post('../controllers/productoController.php',{fu... */
//     $codbar = $_POST['codbar'];
//     $nombre = $_POST['nombre'];
//     $compos = $_POST['compos'];
//     $prod_tipo = $_POST['prod_tipo'];
//     $prod_pres = $_POST['prod_pres'];
//     $precio = $_POST['precio'];
//     $iva = $_POST['iva'];
//     // $adici = $_POST['adici'];
//     // $prod_lab = $_POST['prod_lab'];
//     $product->crearProducto($codbar,$nombre,$compos,$prod_tipo,$prod_pres,$precio,$iva);


// }


if($_POST['funcion'] == 'ultimoReg'){
    /* datos recibidos desde producto.js >>> $.post('../controllers/productoController.php',{fu... */
    // nombre,compos,adici,precio,prod_lab,prod_tipo,prod_present

    $db = new Conexion();
    $conexion = $db->pdo;
    $sql="SELECT MAX(id_prod) AS ultProdM FROM producto";
    $query = $conexion->prepare($sql);
    $query->execute();
    $afg=$query->fetchall();
    // $json=array();
       $jsonstring = json_encode($afg);
    echo $jsonstring;
        // return $this->objetos;

}


/* **************************** EDITAR **************************** */
if($_POST['funcion']=='editar'){
    /* datos recibidos desde producto.js >>> $.post('../controllers/productoController.php',{fu... */
    // nombre,compos,adici,precio,prod_lab,prod_tipo,prod_present
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $compos = $_POST['compos'];
    // $adici = $_POST['adici'];
    $iva = $_POST['iva'];
    $precio = $_POST['precio'];
    // $prod_lab = $_POST['prod_lab'];
    $prod_tipo = $_POST['prod_tipo'];
    $prod_pres = $_POST['prod_pres'];
    // $nombre = $_POST['nombre'];
    $product->editar($id,$nombre,$compos,$prod_tipo,$prod_pres,$precio,$iva);
}

if($_POST['funcion'] =='borrar'){
    /* OJO: $_POST['ID'] viene desde labratorio.js en la const ID = $(ELEM).attr('labId'); */
    $id = $_POST['id'];
    $product->borrar($id);
}

if($_POST['funcion'] =='listar_labs'){
    $lab->listar_labs();
    $json=array();
    foreach($lab->objetos as $objeto){
        $json[]=array(
            'id_lab'=>$objeto->id_lab,
            'nom_lab'=>$objeto->nom_lab
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

/* para cuando se actualiza un precio o el stock del producto, 
la ctualizacion se mostrada en tiempo real (por ejemplo en e carr de compras) */
if($_POST['funcion']=='buscar_id'){
    /* post recibido desde carrito.js : funcion recuperarLS_car ... $.post('../controllers/productoController.php',{funcion,id_producto},(response) */
    $id=$_POST['id_producto'];

    $product->buscar_id($id);
    $json=array();
    foreach($product->objetos as $objeto){
        // /* Funcion que busca en los lotes, los productos con id X, a medida que los va sumando, suma su cantidad */
        // $product->obtenerStock($objeto->id_prod);
        // foreach($product->objetos as $obj){
        //     /* $obj->total: el total viene del alias total en el modelo >> "SELECT SUM(stock) as >>total<< ...*/
        //     $total = $obj->total;
        // }

        $json[]=array(
            /* '' =>$objeto->ALIAS ASIGNADO */
            'id_prod'=>$objeto->id_prod,
            'nombre'=>$objeto->nombre,
            'compos'=>$objeto->compos,
            'precio'=>$objeto->precio,
            'tipo'=>$objeto->tipo,
            'presentacion'=>$objeto->presentacion,
            // 'tipo_id'=>$objeto->prod_tipo,
            // 'pres_id'=>$objeto->prod_pres
        );
    }
    /* los corchetes y elcero es porque se le van a enviar los valores UNO POR UNO */
    $jsonstring = json_encode($json[0]);
    echo $jsonstring;
}

if($_POST['funcion']=='verificar-stock'){
    $error =0;
    $productos = json_decode($_POST['productos']);

    foreach($productos as $objeto){
        /* Funcion que busca en los lotes, los productos con id X, a medida que los va sumando, suma su cantidad */
        $product->obtenerStock($objeto->id_prod);
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
if($_POST['funcion']=='traer_productos'){
    // echo "here";
    $html = "";

    /* 
    * recibir la variable productos enviada desde carrito js en async function recuperarLS_car_compra():
    * body: 'funcion='+funcion+'&&productos='+JSON.stringify(productos)
    * Decodificar el stryngify enviado
    */
    $productos = json_decode($_POST['productos']);

    /* Recorrer la variable productos */
    /* Un $resultado es un producto de toda la lista */
    foreach($productos as $resultado){
        $product->buscar_id($resultado->id_prod);
        // var_dump($product);

        /* Acceder a todos los datos de ese producto al acceder a ->objetos*/
        foreach($product->objetos as $objeto){

            /* Calcular subtotal */
            $subtotal= $objeto->precio * $resultado->cantidad;

            /* Calcular el IVA de un producto */
            $iva = 0.19;            /* Variable IVA */
            $divIva = 1.19;            /* Variable Para restar el iva de un total */
            $i = 0;
            
            /* Restarle el iva del precio establecido del producto
            Mostrara al final el precio base (sin iva) */
            if($objeto->iva == 1){
                $sinIva = $objeto->precio / $divIva;
                $sinIva = round($sinIva,0);  /* Redondear la cifra */
    
                /* Calcular el iva del producto */
                $ivaProd = $sinIva * $iva;
                $ivaProd = round($ivaProd,0);

            }else{
                $sinIva = $objeto->precio;
                $ivaProd = 0;
            }


            /* Obtener el stock */
            $product->obtenerStock($objeto->id_prod);
            foreach($product->objetos as $obj){
                $stock = $obj->total;
            }

            $html.="
            <tr prodId='$objeto->id_prod' prodPrecio='$objeto->precio'>
                <td>$objeto->nombre</td>
                <td>$stock</td>
                <td class='precio'>$objeto->precio</td>
                <td>$objeto->compos</td>
                <td>$sinIva</td>
                <td>$ivaProd</td>
                <td>$objeto->laboratorio</td>
                <td>$objeto->presentacion</td>
                <td>
                    <input type='text' min='1' class='form-control cant_producto' value='$resultado->cantidad'>
                </td>
                <td class='subtotales'>
                    <h5>$subtotal</h5>
                </td>
                <td><button class='btn btn-danger borrar-producto' ><i class='fas fa-times-circle'></i></button></td>
            </tr>
            ";
        }
    }
    echo $html;
}


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
                    <th>Presentacion</th>
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

        $product->obtenerStock($objeto->id_prod);
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
                <th class="service">'.$objeto->presentacion.'</th>
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
if($_POST['funcion']=='buscaCodbar'){
    $consulta = $_POST['consulta'];
    $product->buscarCodbarModel($consulta);
    $json=array();
    foreach($product->objetos as $objeto){
        /* Funcion que busca en los lotes, los productos con id X, a medida que los va sumando, suma su cantidad */
        $product->obtenerStock($objeto->id_prod);
        foreach($product->objetos as $obj){
            /* $obj->total: el total viene del alias total en el modelo >> "SELECT SUM(stock) as >>total<< ...*/
            $total = $obj->total;
        }

        $json[]=array(
            /* '' =>$objeto->ALIAS ASIGNADO */
            'id_prod'=>$objeto->id_prod,
            'nombre'=>$objeto->nombre,
            'compos'=>$objeto->compos,
            'adici'=>$objeto->adici,
            'iva'=>$objeto->iva,
            'precio'=>$objeto->precio,
            'stock'=>$total,
            'lab_id'=>$objeto->prod_lab,
            'tipo_id'=>$objeto->prod_tipo,
            'pres_id'=>$objeto->prod_pres
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}


// if($_POST['funcion'] == 'registrarProduct'){
//     // funcion, codbar, prod_tipo, nombre, prod_pres, precio, iva, json

//     $codbar     = $_POST['codbar'];
//     $prod_tipo  = $_POST['prod_tipo'];
//     $nombre     = $_POST['nombre'];
//     $prod_pres  = $_POST['prod_pres'];
//     $precio     = $_POST['precio'];
//     $iva        = $_POST['iva'];


//     $ingreds = json_decode($_POST['jsonIngreds']);

//     $product->crearProducto($codbar,$nombre,$prod_tipo,$prod_pres,$precio,$iva);

//     /* obtener ultimo producto registrado */
//     $product->cargarUltimoProdReg();
//     foreach($product->objetos as $objeto){
//         $idProduct = $objeto->ultimoreg;
//         echo $idProduct;
//     }

//     try {
//         $db = new Conexion();
//         $conexion = $db->pdo;
//         $conexion->beginTransaction();
//         foreach ($ingreds as $ingred) {
//             $cantidad = $ingred->cantidad;

//             while($cantidad != 0){
//                 $sql = "INSERT INTO ingrediente(cant,id_ing_prod,id_prod) 
//                     VALUES(
//                         '$cantidad',
//                         '$ingred->id_prod',
//                         $idProduct
//                     )
//                 ";
//                 $conexion->exec($sql);
//                 $cantidad = 0;
//             }
//         }

//         $conexion->commit();

//         } catch (Exception $error) {
//             $conexion->rollBack();
//         echo $error->getMessage();
//     }

// }


if($_POST['funcion'] == 'nuevoItem'){

    $codbar     = $_POST['codbar'];
    $cat_item   = $_POST['cat_item'];
    $nombreItem = $_POST['nombre'];
    $pres_item  = $_POST['pres_item'];
    $precio     = $_POST['precio'];
    $iva        = $_POST['iva'];
    $idNuevoItem= 0;

    $ingreds    = json_decode($_POST['json']);

    $respCrearItemMenu = $product->crearProducto($codbar, $cat_item, $nombreItem, $pres_item, $precio, $iva);

    if($respCrearItemMenu){

        /* obtener ultimo producto registrado */
        $product->cargarUltimoProdReg();
        foreach($product->objetos as $objeto){
            $idNuevoItem = $objeto->ultimoreg;
            // echo $idProduct;
        }

        
        foreach ($ingreds as $ingred) {
            $idIngred  = $ingred->id_prod;
            $nomIngred = $ingred->nombre;
            $medida    = $ingred->medida;
            $cantidad  = $ingred->cantidad;
    
    
            $product->crearItemMenu($idNuevoItem, $idIngred, $nomIngred, $medida, $cantidad);
        }
        echo "amadio e item";

    }else{
        echo "error al add";
    }



}