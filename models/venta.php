<?php
include 'conexion.php';
class Venta{
    var $objetos;
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }

    function crear($nombre,$total,$totIvaEx,$totBaseIvaEx,$valIvaEx,$totIvaAp,$totBaseIvaAp,$valIvaAp,$baseTotal,$ivaTotal,$fecha,$vendedor){
            // funcion,total,totIvaEx,totBaseIvaEx,valIvaEx,totIvaAp,totBaseIvaAp,valIvaAp,baseTotal,ivaTotal,json,nombre

        $sql = "INSERT INTO venta(fecha, cliente, total,totIvaEx,totBaseIvaEx,valIvaEx,totIvaAp,totBaseIvaAp,valIvaAp,baseTotal,ivaTotal, vendedor) 
        VALUES (:fecha, :cliente, :total, :totIvaEx, :totBaseIvaEx, :valIvaEx, :totIvaAp, :totBaseIvaAp, :valIvaAp, :baseTotal, :ivaTotal,:vendedor)";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':fecha'       => $fecha,
            ':cliente'       => $nombre,
            ':total'        => $total,
            ':totIvaEx'   => $totIvaEx,
            ':totBaseIvaEx' => $totBaseIvaEx,
            ':valIvaEx'   => $valIvaEx,
            ':totIvaAp' => $totIvaAp,
            ':totBaseIvaAp'   => $totBaseIvaAp,
            ':valIvaAp' => $valIvaAp,
            ':baseTotal'  => $baseTotal,
            ':ivaTotal' => $ivaTotal,
            ':vendedor'  => $vendedor
        ));
        echo 'add';
    }

    function ultimaVenta(){
        $sql="SELECT MAX(id_venta) AS ultima_venta FROM venta";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }

    function borrar($idVenta){
        $sql = "DELETE FROM venta WHERE id_venta=:id_venta";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_venta' => $idVenta));

    }

    function buscar(){
        $sql="SELECT id_venta, fecha, total, CONCAT(usuario.nom,' ',usuario.ape) AS vendedor FROM venta 
        JOIN usuario ON vendedor = id_usu";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    /* ESTA FUNCON SE TIENE QUE MODIFICAR PARA LA GENERACION DE PDF */
    function buscar_id($id){
        // $id = 31;
        $sql="SELECT id_venta,fecha,cliente,total, CONCAT(usuario.nom,' ',usuario.ape) AS vendedor, totivaex,totbaseivaex,valivaex,totivaap,totbaseivaap,valivaap,basetotal,ivatotal 
        FROM venta 
        
        JOIN usuario ON vendedor = id_usu
        
        WHERE id_venta = :id
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }
    // /* ESTA FUNCON SE TIENE QUE MODIFICAR PARA LA GENERACION DE PDF */
    // function buscar_id($id){
    //     // $id = 31;
    //     $sql="SELECT * FROM venta 
        
    //     JOIN usuario ON vendedor = id_usu
        
    //     WHERE id_venta = :id
    //     ";
    //     $query = $this->acceso->prepare($sql);
    //     $query->execute(array(':id'=>$id));
    //     $this->objetos=$query->fetchall();
    //     return $this->objetos;
    // }


    /* XP VENTA PRODUCTO */
    /* JOIN nimbreTabla(que contiene la llave primaria) ON llaveforanea = llave primaria de nimbreTabla*/
    function ver($id){
        // echo $id;
        $sql="SELECT venta_prod.precio AS precio, cant, producto.nombre AS producto, compos,adici, laboratorio.nom_lab AS laboratorio,
        present.nom as presentacion, tipo_prod.nom as tipo, subtotal, prod_id_prod 
        FROM venta_prod 
        JOIN producto ON prod_id_prod = id_prod AND venta_id_venta = :id
        JOIN laboratorio ON prod_lab = id_lab
        JOIN tipo_prod ON prod_tipo = id_tipo_prod
        JOIN present ON prod_pres = id_present
        ";

        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    /* Lo que vende un vendedor en un dia */
    // function venta_dia_vendor($id){
    //     $sql="SELECT SUM(total) as venta_dia_vendor FROM `venta` WHERE vendedor=:id_usuario AND DATE(fecha) = DATE(curdate())";

    //     $query = $this->acceso->prepare($sql);
    //     $query->execute(array(':id_usuario' => $id));
    //     $this->objetos=$query->fetchall();
    //     return $this->objetos;
    // }

    // $sql="SELECT venta_prod.cant as cant, venta_prod.subtotal as subtotal, producto.nombre AS producto FROM `venta`


    /************************************************/
    /************** VENTAS POR... *******************/
    /************************************************/

    // Venta por dia y vendedor
    function venta_dia_vendor($id){
        $sql="SELECT SUM(total) as venta_dia_vendor FROM `venta` 
        WHERE vendedor=:id_usuario AND DATE(fecha) = DATE(curdate())";

        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_usuario' => $id));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }

    //Venta diaria total
    function venta_dia(){
        $sql="SELECT SUM(total) as venta_dia FROM `venta` 
        WHERE DATE(fecha) = DATE(curdate())";

        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }

    //Venta Mensual total
    function venta_mensual(){
        $sql="SELECT SUM(total) as venta_mensual FROM `venta` 
        WHERE year(fecha) = year(curdate()) AND month(fecha) = month(curdate())";

        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }

    //Venta Anual total
    function venta_anual(){
        $sql="SELECT SUM(total) as venta_anual FROM `venta` 
        WHERE year(fecha) = year(curdate())";

        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    // Venta diaria Tabla
    function venta_diaria($idUsu){
        $sql="SELECT id_venta, producto.nombre AS producto, venta_prod.cant as cantidad, venta_prod.subtotal as subtotal 
        FROM `venta`
        JOIN venta_prod on venta_id_venta = id_venta
        JOIN producto ON prod_id_prod = id_prod
         WHERE vendedor=:id_usuario AND DATE(fecha) = DATE(curdate()) ORDER BY id_venta ASC";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_usuario' => $idUsu));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }
    // function venta_diaria(){
    //     $sql="SELECT id_venta, producto.nombre AS producto, venta_prod.cant as cantidad, venta_prod.subtotal as subtotal 
    //     FROM `venta`
    //     JOIN venta_prod on venta_id_venta = id_venta
    //     JOIN producto ON prod_id_prod = id_prod
    //      WHERE DATE(fecha) = DATE(curdate()) ORDER BY id_venta ASC";
    //     $query = $this->acceso->prepare($sql);
    //     $query->execute();
    //     $this->objetos=$query->fetchall();
    //     return $this->objetos;
    // }
 

    /* Calcular el total de la venta diaria */
    function totalVentas(){
        $sql="SELECT SUM(total) as venta_dia_vendor FROM `venta` WHERE DATE(fecha) = DATE(curdate())";

        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
        // $sql="SELECT SUM(venta_prod.subtotal) as grantotal FROM `venta`
        //     WHERE DATE(fecha) = DATE(curdate())";
        // $query = $this->acceso->prepare($sql);
        // $query->execute();
        // $this->objetos=$query->fetchall();
        // return $this->objetos;

    }

    function consultarResponsables($idVenta){
        $sql = "SELECT id_mesero, id_coc_lider, id_orden FROM venta
        WHERE id_venta = :id_venta";
    
        $query = $this->acceso->prepare($sql);
        $query->execute([':id_venta' => $idVenta]);
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }

    function consultarMesero($id){
        $sql = "SELECT CONCAT(usuario.nom,' ',usuario.ape) AS mesero FROM usuario
        WHERE id_usu = :id";
    
        $query = $this->acceso->prepare($sql);
        $query->execute([':id' => $id]);
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }

    function consultarCocineroLider($id){
        $sql = "SELECT CONCAT(usuario.nom,' ',usuario.ape) AS cocinerolider FROM usuario
        WHERE id_usu = :id";
    
        $query = $this->acceso->prepare($sql);
        $query->execute([':id' => $id]);
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }

    function consultarObservaciones($idOrden){
        $sql = "SELECT observ FROM pedido
        WHERE id_pedido = :idOrden";
    
        $query = $this->acceso->prepare($sql);
        $query->execute([':idOrden' => $idOrden]);
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }


}