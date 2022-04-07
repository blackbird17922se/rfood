<?php
include 'conexion.php';
class DescuadreInv{
    var $objetos;

    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }

    public function cargarDescuadres(){
        $sql=
            "SELECT 
                id_descuadre,
                fecha_venta,
                id_venta,
                INGR.nombre AS nom_ingred,
                CONCAT(cantidad,' ',MEDIDA.nom) AS cantidad
            FROM INV_DESCUADRE AS INV_DESC
            LEFT JOIN ingred INGR
            ON INGR.id_inv_prod = INV_DESC.id_ingred
            LEFT JOIN un_medida MEDIDA
            ON MEDIDA.id_medida = INGR.un_medida";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }

    public function borrar($id){
        $sql = "DELETE FROM INV_DESCUADRE WHERE id_descuadre=:id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id));

        if(!empty($query->execute(array(':id' => $id)))){
            echo 1;
        }else{
            echo 0;
        }
    }

}