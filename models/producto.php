<?php
include 'conexion.php';
class Producto{
    var $objetos;
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }
    

    //15-11
    function crearProducto($codbar, $cat_item, $nombreItem, $pres_item, $precio, $iva){

        $sql = "SELECT id_prod FROM producto WHERE codbar = :codbar";

        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':codbar'       => $codbar
        ));
        $this->objetos=$query->fetchall();
        /* Si encuentra el producto entonces no agregarlo */
        if(!empty($this->objetos)){
            echo 'noadd';
        }else{
            $sql = "INSERT INTO producto(codbar, nombre, prod_tipo, prod_pres, precio, iva) 
            VALUES (:codbar, :nombre, :prod_tipo, :prod_pres, :precio, :iva)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(
                ':codbar'       => $codbar,
                ':nombre'       => $nombreItem,
                ':prod_tipo'    => $cat_item,
                ':prod_pres'    => $pres_item,
                ':precio'       => $precio,
                ':iva'          => $iva
            ));

            echo 'add_Item_Menu';
            return true;
        }


        
    }


    function listarProducts(){

        $sql = "SELECT id_prod, codbar, producto.nombre as nombre,  iva, precio, tipo_prod.nom AS tipo, present.nom AS presentacion, prod_tipo, prod_pres
        FROM producto
        JOIN tipo_prod ON prod_tipo = id_tipo_prod
        JOIN present ON prod_pres = id_present AND producto.nombre NOT LIKE ''
        ORDER BY producto.nombre
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }


    /* 
     'id_prod'=>$objeto->id_prod,
            'codbar'=>$objeto->codbar,
            'nombre'=>$objeto->nombre,
            'compos'=>$objeto->
            'categoria'=>$objeto->prod_tipo,    //Categoria
            'prod_pres'=>$objeto->prod_pres,
            'precio'=>$objeto->precio,
            'cant'=>$cant,
             */





    function editar($id,$nombre, $prod_tipo,$prod_pres,$precio,$iva){
        // $sql = "UPDATE producto SET
        //         nombre = :nombre, 
        //         prod_tipo = :prod_tipo, 
        //         prod_pres = :prod_pres,
        //         precio = :precio, 
        //         iva = :iva,
        //     WHERE id_prod = :id";
        // $query = $this->acceso->prepare($sql);
        // $query->execute(array(
        //     ':id'           => $id,
        //     ':nombre'       => $nombre,
        //     ':prod_tipo'    => $prod_tipo,
        //     ':prod_pres'    => $prod_pres,
        //     ':precio'       => $precio,
        //     ':iva'          => $iva,
        // ));
        // echo 'edit';
    }





    /* Sumar "sum()" todos los campos stock, esa suma se llamara total, buscara todos lotes con un id producto X
    y a esos lotes los mumara todo el stock */
    function obtenerStock($id_prod){
        $sql = "SELECT SUM(stock) as total FROM lote WHERE lote_id_prod = :id_prod";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_prod' => $id_prod));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    /* para cuando se actualiza un precio o el stock del producto, 
    la ctualizacion se mostrada en tiempo real (por ejemplo en e carr de compras) */
    function buscar_id($id){
        $sql="SELECT id_prod, producto.nombre as nombre,  precio, tipo_prod.nom AS tipo, present.nom AS presentacion, prod_tipo, prod_pres
        FROM producto
        JOIN tipo_prod ON prod_tipo = id_tipo_prod
        JOIN present ON prod_pres = id_present WHERE id_prod = :id
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    /* FUNCION PARA QUE TRAIGA TODOS OS PRODUCTOS PARA EL PDF */
    function reporteProductos(){

        $sql = "SELECT id_prod, producto.nombre as nombre,  adici, precio, laboratorio.nom_lab AS laboratorio, tipo_prod.nom AS tipo, present.nom AS presentacion, prod_lab, prod_tipo, prod_pres
        FROM producto
        JOIN laboratorio ON prod_lab = id_lab
        JOIN tipo_prod ON prod_tipo = id_tipo_prod
        JOIN present ON prod_pres = id_present AND producto.nombre NOT LIKE ''
        ORDER BY producto.nombre
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }




    /* *************CODBAR****************** */
    function buscarCodbarModel($consulta){
        $sql="SELECT * FROM producto WHERE codbar = :codbar";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':codbar'=>$consulta));
        $this->objetos=$query->fetchall();
        return $this->objetos;

        // if(!empty($_POST['consulta'])){
        //     // echo $_POST['consulta'];
        //     /* si el imput de bsiqueda esta lleno entonces */
        //     $consulta = 70707015506093;
        //     // $consulta = $_POST['consulta'];
        //     $sql="SELECT id_prod, producto.nombre as nombre,  adici, precio, laboratorio.nom_lab AS laboratorio, tipo_prod.nom AS tipo, present.nom AS presentacion, prod_lab, prod_tipo, prod_pres
        //     FROM producto WHERE codbar = :codbar
        //     -- JOIN laboratorio ON prod_lab = id_lab
        //     -- JOIN tipo_prod ON prod_tipo = id_tipo_prod
        //     -- JOIN present ON prod_pres = id_present AND producto.nombre LIKE :consulta
        //     ";
        //     $query = $this->acceso->prepare($sql);
        //     $query->execute(array(':consulta'=>"%$consulta%"));
        //     $this->objetos=$query->fetchall();
        //     return $this->objetos;
        // }else{
        //     // $sql = "SELECT id_prod, producto.nombre as nombre,  adici, precio, laboratorio.nom_lab AS laboratorio, tipo_prod.nom AS tipo, present.nom AS presentacion, prod_pres, prod_lab, prod_tipo, prod_pres
        //     // $sql = "SELECT id_prod, producto.nombre as nombre,  adici, precio, laboratorio.nom_lab AS laboratorio, tipo_prod.nom AS tipo, present.nom AS presentacion, prod_lab, prod_tipo, prod_pres
        //     // FROM producto
        //     // JOIN laboratorio ON prod_lab = id_lab
        //     // JOIN tipo_prod ON prod_tipo = id_tipo_prod
        //     // JOIN present ON prod_pres = id_present AND producto.nombre NOT LIKE ''
        //     // ORDER BY producto.nombre
        //     // ";
        //     // $query = $this->acceso->prepare($sql);
        //     // $query->execute();
        //     // $this->objetos=$query->fetchall();
        //     // return $this->objetos;
        //     echo "error";
        // }
    }

    function cargarUltimoProdReg(){

        $sql="SELECT MAX(id_prod) AS ultimoreg FROM producto";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;

    }

    function crearItemMenu($idNuevoItem, $idIngred, $nomIngred, $medida, $cantidad){
        /* Agregar a la tabla ingredintes */
        $sql = "INSERT INTO ITEMENUINGR(ID_ITEM, ID_INGR, NOM_INGR,
        MEDIDA_INGR, CANT_INGR)
        VALUES(
            :idNuevoItem, :idIngred, :nomIngred, :medida, :cantidad
        )              
    ";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(
        ':idNuevoItem'    => $idNuevoItem,
        ':idIngred'  => $idIngred,
        ':nomIngred' => $nomIngred,
        ':medida'    => $medida,
        ':cantidad'  => $cantidad,
    ));
    echo 'add_Ingred_item / ';


    }
}