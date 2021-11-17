<?php
include 'conexion.php';
class InvMedida{
    var $objetos;
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }

    function crearmedida($nom){
        $sql = "SELECT id_medida FROM un_medida WHERE nom = :nom";
        $query = $this->acceso->prepare($sql);
        $query->execute(array('nom' => $nom));
        $this->objetos=$query->fetchall();
        if(!empty($this->objetos)){
            echo 'noadd';
        }else{
            $sql = "INSERT INTO un_medida(nom) VALUES (:nom)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array('nom' => $nom));
            echo 'add';
        }
    }

    function consultarmedidas(){
        if(!empty($_POST['consulta'])){
            $consulta = $_POST['consulta'];
            $sql="SELECT * FROM un_medida WHERE nom LIKE :consulta";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':consulta'=>"%$consulta%"));
            $this->objetos=$query->fetchall();
            return $this->objetos;
        }else{
            $sql = "SELECT * FROM un_medida WHERE nom NOT LIKE '' ORDER BY id_medida";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos=$query->fetchall();
            return $this->objetos;
        }
    }

    function editarmedida($nom,$id_editado){
        $sql = "UPDATE un_medida SET nom = :nom WHERE id_medida = :id_medida";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_medida' => $id_editado,':nom' => $nom));
        echo 'edit';
    }

    
    function borrarmedida($id_medida){
        $sql = "DELETE FROM un_medida WHERE id_medida = :id_medida";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_medida' => $id_medida));
        // echo 'borrado';

        if(!empty($query->execute(array(':id_medida' => $id_medida)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }

    }

    function listarInvmedidas(){
        // $consulta = $_POST['consulta'];
        $sql="SELECT * FROM un_medida ORDER BY nom ASC";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }

    function consultarDatosMedida($idMedida){

        $sql="SELECT * FROM un_medida WHERE ID_MEDIDA = :idMedida";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':idMedida' => $idMedida));
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }
}