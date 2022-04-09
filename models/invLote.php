<?php
include 'conexion.php';
class InvLote{
    var $objetos;
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }

    function crear($lote_id_prod,$lote_id_prov,$stock,$vencim){

        $sql = "INSERT INTO inv_lote (lote_id_prod, lote_id_prov, stock, vencim) 
        VALUES (:lote_id_prod, :lote_id_prov, :stock, :vencim)";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':lote_id_prod'     => $lote_id_prod,
            ':lote_id_prov'     => $lote_id_prov,
            ':stock'            => $stock,
            ':vencim'           => $vencim
        ));
        echo 'add';
    }


    function cargarLotes(){
        $sql="SELECT id_lote, stock, vencim, ingred.nombre as prod_nom, 
        inv_tipo_prod.nom AS tipo_nom, un_medida.nom AS medida_nom, proveed.nom AS prov_nom
       FROM inv_lote
       /* Nombr tabla ON llave foranea = llave primaria de la tabla JOIN */
       JOIN proveed ON lote_id_prov = id_prov
       JOIN ingred ON lote_id_prod = id_inv_prod
       JOIN inv_tipo_prod ON prod_tipo = id_inv_tipo
       JOIN un_medida ON un_medida = id_medida AND ingred.nombre NOT LIKE '' ORDER BY ingred.nombre";
       $query = $this->acceso->prepare($sql);
       $query->execute();
       $this->objetos=$query->fetchall();
       return $this->objetos;
    }

    function editar($lote_id_prod,$stock){
        $sql = "UPDATE inv_lote SET
            stock = :stock

        WHERE id_lote = :id_lote";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':id_lote' => $lote_id_prod,
            ':stock'     => $stock
            
        ));
        echo 'edit';
    }


    function borrar($id){
        $sql = "DELETE FROM inv_lote WHERE id_lote = :id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id));

        if(!empty($query->execute(array(':id' => $id)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }
    }
}
