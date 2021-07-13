<?php
include 'conexion.php';
class InvProducto{
    var $objetos;
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }
    

    function crear($codbar,$nombre,$iva,$precio,$prod_tipo,$un_medida){
        $sql = "SELECT id_inv_prod FROM inv_producto WHERE codbar = :codbar";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':codbar'       => $codbar

        ));
        $this->objetos=$query->fetchall();
        /* Si encuentra el inv_producto entonces no agregarlo */
        if(!empty($this->objetos)){
            echo 'noadd';
        }else{
            $sql = "INSERT INTO inv_producto(codbar, nombre, iva, precio, prod_tipo, un_medida) 
            VALUES (:codbar, :nombre, :iva, :precio, :prod_tipo, :un_medida)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(
                ':codbar'       => $codbar,
                ':nombre'       => $nombre,
              
                ':iva'          => $iva,
                ':precio'       => $precio,
                ':prod_tipo'    => $prod_tipo,
                ':un_medida'    => $un_medida
            ));
            echo 'add';
        }
    }


    function listarProducts(){

        $sql = "SELECT id_inv_prod, codbar, inv_producto.nombre as nombre, iva, precio, inv_tipo_prod.nom AS tipo, un_medida.nom AS medida, prod_tipo, un_medida
        FROM inv_producto
        JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        JOIN un_medida ON un_medida = id_medida AND inv_producto.nombre NOT LIKE ''
        ORDER BY inv_producto.nombre
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }


    // /* LISTA TABLA */
    // function listarProducts(){
    //     $sql="SELECT * FROM producto 
    //     -- JOIN usuario ON vendedor = id_usu";
    //     $query = $this->acceso->prepare($sql);
    //     $query->execute();
    //     $this->objetos=$query->fetchall();
    //     return $this->objetos;
    // }


    function editar($id,$nombre,$compos,$adici,$iva,$precio,$prod_tipo,$un_medida){
        $sql = "SELECT id_inv_prod FROM producto WHERE id_inv_prod != :id
        -- $nombre,$compos,$adici,$precio,$prod_tipo,$un_medida
            AND nombre = :nombre 
            AND compos = :compos 
            AND adici = :adici 
            -- AND precio = :precio 
            AND prod_lab = :prod_lab 
            AND prod_tipo = :prod_tipo 
            AND un_medida = :un_medida
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':id'           => $id,
            ':nombre'       => $nombre,
            ':compos'       => $compos,
            ':adici'        => $adici,
            // ':precio'       => $precio,
            ':prod_tipo'    => $prod_tipo,
            ':un_medida' => $un_medida
            // 'nom_lab' => $nom_lab,
        ));
        $this->objetos=$query->fetchall();
        /* Si encuentra el producto entonces no agregarlo */
        if(!empty($this->objetos)){
            echo 'noedit';
        }else{
            $sql = "UPDATE producto SET
                 nombre = :nombre, 
                 compos = :compos, 
                 adici = :adici,
                 iva = :iva,
                 precio = :precio, 
                 prod_lab = 
                 prod_tipo = :prod_tipo, 
                 un_medida = :un_medida
                WHERE id_inv_prod = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(
                ':id'           => $id,
                ':nombre'       => $nombre,
              
                ':iva'        => $iva,
                ':precio'       => $precio,
                ':prod_tipo'    => $prod_tipo,
                ':un_medida'    => $un_medida
            ));
            echo 'edit';
        }
    }


    function borrar($id){
        $sql = "DELETE FROM producto WHERE id_inv_prod = :id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id));

        if(!empty($query->execute(array(':id' => $id)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }
    }


    /* Sumar "sum()" todos los campos stock, esa suma se llamara total, buscara todos lotes con un id producto X
    y a esos lotes los mumara todo el stock */
    function obtenerStock($id_inv_prod){
        $sql = "SELECT SUM(stock) as total FROM inv_lote WHERE lote_id_prod = :id_inv_prod";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_inv_prod' => $id_inv_prod));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    /* para cuando se actualiza un precio o el stock del producto, 
    la ctualizacion se mostrada en tiempo real (por ejemplo en e carr de compras) */
    function buscar_id($id){
        $sql="SELECT id_inv_prod, producto.nombre as nombre, iva, precio, laboratorio.nom_lab AS laboratorio, inv_tipo_prod.nom AS tipo, present.nom AS presentacion, prod_tipo, un_medida
        FROM producto
        JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        JOIN present ON un_medida = id_medida WHERE id_inv_prod = :id
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    /* FUNCION PARA QUE TRAIGA TODOS OS PRODUCTOS PARA EL PDF */
    function reporteProductos(){

        $sql = "SELECT id_inv_prod, producto.nombre as nombre, precio, laboratorio.nom_lab AS laboratorio, inv_tipo_prod.nom AS tipo, present.nom AS presentacion, prod_tipo, un_medida
        FROM producto
        JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        JOIN present ON un_medida = id_medida AND producto.nombre NOT LIKE ''
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
        //     $sql="SELECT id_inv_prod, producto.nombre as nombre, precio, laboratorio.nom_lab AS laboratorio, inv_tipo_prod.nom AS tipo, present.nom AS presentacion, prod_tipo, un_medida
        //     FROM producto WHERE codbar = :codbar
        //     -- JOIN laboratorio ON prod_lab = id_lab
        //     -- JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        //     -- JOIN present ON un_medida = id_medida AND producto.nombre LIKE :consulta
        //     ";
        //     $query = $this->acceso->prepare($sql);
        //     $query->execute(array(':consulta'=>"%$consulta%"));
        //     $this->objetos=$query->fetchall();
        //     return $this->objetos;
        // }else{
        //     // $sql = "SELECT id_inv_prod, producto.nombre as nombre, precio, laboratorio.nom_lab AS laboratorio, inv_tipo_prod.nom AS tipo, present.nom AS presentacion, un_medida, prod_tipo, un_medida
        //     // $sql = "SELECT id_inv_prod, producto.nombre as nombre, precio, laboratorio.nom_lab AS laboratorio, inv_tipo_prod.nom AS tipo, present.nom AS presentacion, prod_tipo, un_medida
        //     // FROM producto
        //     // JOIN laboratorio ON prod_lab = id_lab
        //     // JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        //     // JOIN present ON un_medida = id_medida AND producto.nombre NOT LIKE ''
        //     // ORDER BY producto.nombre
        //     // ";
        //     // $query = $this->acceso->prepare($sql);
        //     // $query->execute();
        //     // $this->objetos=$query->fetchall();
        //     // return $this->objetos;
        //     echo "error";
        // }
    }

    function ultimoProd(){
        // $sql="SELECT * FROM producto";
        // $sql="SELECT MAX(id_inv_prod) FROM producto";
        $sql="SELECT MAX(id_inv_prod) AS ultProdM FROM producto";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;

        // $sql = "SELECT MAX(`id_inv_prod`) FROM `producto`";
        // $query = $this->acceso->prepare($sql);
        // $query->execute();
        // $this->objetos=$query->fetchall();
        // return $this->objetos;
    }
}