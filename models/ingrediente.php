<?php
include 'conexion.php';
class Ingrediente{
    var $objetos;
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }
    

    function crearProducto($codbar,$nombre,$compos,$prod_tipo,$un_medida,$precio,$iva){
        $sql = "SELECT id_inv_prod FROM producto WHERE codbar = :codbar";

        // nombre = :nombre
        // AND codbar = :codbar 
        // AND compos = :compos 
        // AND adici = :adici
        // AND prod_lab = :prod_lab 
        // AND prod_tipo = :prod_tipo 
        // AND un_medida = :un_medida


        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':codbar'       => $codbar
            // ':nombre'       => $nombre,
            // ':compos'       => $compos,
            // ':adici'        => $adici,
            // ':prod_lab'     => $prod_lab,
            // ':prod_tipo'    => $prod_tipo,
            // ':un_medida' => $un_medida
        ));
        $this->objetos=$query->fetchall();
        /* Si encuentra el producto entonces no agregarlo */
        if(!empty($this->objetos)){
            echo 'noadd';
        }else{
            /* codbar,$nombre,$compos,$prod_tipo,$un_medidaent,$precio,$iva */
            $sql = "INSERT INTO producto(codbar, nombre, prod_tipo, un_medida, precio, iva) 
            VALUES (:codbar, :nombre,  :prod_tipo, :un_medida, :precio, :iva)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(
                ':codbar'       => $codbar,
                ':nombre'       => $nombre,
                ':compos'       => $compos,
                ':prod_tipo'    => $prod_tipo,
                ':un_medida'    => $un_medida,
                ':precio'       => $precio,
                ':iva'          => $iva
            ));
            echo 'add';
        }
    }


    function listarProducts(){

        $sql = "SELECT id_inv_prod, codbar, producto.nombre as nombre, iva, precio, inv_tipo_prod.nom AS tipo, un_medida.nom AS un_medidaacion, prod_tipo, un_medida
        FROM producto
        JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        JOIN un_medida ON un_medida = id_medida AND producto.nombre NOT LIKE ''
        ORDER BY producto.nombre
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }


    /* 
     'id_inv_prod'=>$objeto->id_inv_prod,
            'codbar'=>$objeto->codbar,
            'nombre'=>$objeto->nombre,
            'compos'=>$objeto->compos,
            'categoria'=>$objeto->prod_tipo,    //Categoria
            'un_medida'=>$objeto->un_medida,
            'precio'=>$objeto->precio,
            'cant'=>$cant,
             */


    /* Listar productos recibiendo la categoria de producto a listar.
    Usada en la toma de la orden */
    function listarProductsCateg($idCat){

        $sql = "SELECT id_inv_prod, codbar, inv_tipo_prod.nom AS categ, nombre, un_medida, un_medida.nom AS medida, precio 
        FROM inv_producto 
        JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        JOIN un_medida ON un_medida = id_medida
        WHERE prod_tipo = :idCat";
        $query = $this->acceso->prepare($sql);
        $query->execute([':idCat' => $idCat]);
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }


    function editar($id,$nombre,$compos,$prod_tipo,$un_medida,$precio,$iva){
        $sql = "UPDATE producto SET
                nombre = :nombre, 
                prod_tipo = :prod_tipo, 
                un_medida = :un_medida,
                precio = :precio, 
                iva = :iva,
                compos = :compos
            WHERE id_inv_prod = :id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':id'           => $id,
            ':nombre'       => $nombre,
            ':prod_tipo'    => $prod_tipo,
            ':un_medida'    => $un_medida,
            ':precio'       => $precio,
            ':iva'          => $iva,
            ':compos'       => $compos
        ));
        echo 'edit';
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
        $sql = "SELECT SUM(stock) as total FROM lote WHERE lote_id_inv_prod = :id_inv_prod";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_inv_prod' => $id_inv_prod));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    /* para cuando se actualiza un precio o el stock del producto, 
    la ctualizacion se mostrada en tiempo real (por ejemplo en e carr de compras) */
    function buscar_id($id){
        $sql="SELECT id_inv_prod, producto.nombre as nombre, precio, inv_tipo_prod.nom AS tipo, un_medida.nom AS un_medidaacion, prod_tipo, un_medida
        FROM producto
        JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        JOIN un_medida ON un_medida = id_medida WHERE id_inv_prod = :id
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    /* FUNCION PARA QUE TRAIGA TODOS OS PRODUCTOS PARA EL PDF */
    // function reporteProductos(){

    //     $sql = "SELECT id_inv_prod, producto.nombre as nombre, adici, precio, laboratorio.nom_lab AS laboratorio, inv_tipo_prod.nom AS tipo, un_medida.nom AS un_medidaacion, prod_lab, prod_tipo, un_medida
    //     FROM producto
    //     JOIN laboratorio ON prod_lab = id_lab
    //     JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
    //     JOIN un_medida ON un_medida = id_medida AND producto.nombre NOT LIKE ''
    //     ORDER BY producto.nombre
    //     ";
    //     $query = $this->acceso->prepare($sql);
    //     $query->execute();
    //     $this->objetos=$query->fetchall();
    //     return $this->objetos;
        
    // }




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
        //     $sql="SELECT id_inv_prod, producto.nombre as nombre, adici, precio, laboratorio.nom_lab AS laboratorio, inv_tipo_prod.nom AS tipo, un_medida.nom AS un_medidaacion, prod_lab, prod_tipo, un_medida
        //     FROM producto WHERE codbar = :codbar
        //     -- JOIN laboratorio ON prod_lab = id_lab
        //     -- JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        //     -- JOIN un_medida ON un_medida = id_medida AND producto.nombre LIKE :consulta
        //     ";
        //     $query = $this->acceso->prepare($sql);
        //     $query->execute(array(':consulta'=>"%$consulta%"));
        //     $this->objetos=$query->fetchall();
        //     return $this->objetos;
        // }else{
        //     // $sql = "SELECT id_inv_prod, producto.nombre as nombre, adici, precio, laboratorio.nom_lab AS laboratorio, inv_tipo_prod.nom AS tipo, un_medida.nom AS un_medidaacion, un_medida, prod_lab, prod_tipo, un_medida
        //     // $sql = "SELECT id_inv_prod, producto.nombre as nombre, adici, precio, laboratorio.nom_lab AS laboratorio, inv_tipo_prod.nom AS tipo, un_medida.nom AS un_medidaacion, prod_lab, prod_tipo, un_medida
        //     // FROM producto
        //     // JOIN laboratorio ON prod_lab = id_lab
        //     // JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
        //     // JOIN un_medida ON un_medida = id_medida AND producto.nombre NOT LIKE ''
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