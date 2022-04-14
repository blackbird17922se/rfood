<?php
include_once "../models/usuario.php";
session_start();
$user = $_POST['user'];
$pass = $_POST['pass'];

$usuario = new Usuario();

// si hay una sesion en curso..
if(!empty($_SESSION['rol'])){

    header("Location: ../views/main.php");

}else{

    // ejecutar consulta
    if(!empty($usuario->logIn($user,$pass)=="logueado")){
        $usuario->obtenerDatosLog($user);
        foreach($usuario->objetos as $objeto){
            $_SESSION['usuario'] = $objeto->id_usu;
            $_SESSION['rol'] = $objeto->rol;
            $_SESSION['nom'] = $objeto->nom;
        }
        $token = sha1(uniqid(rand(), true));

        setcookie("tk",$token,time()+(60*60*24*31),"/");
        $_SESSION['token'] = $token;

        header("Location: ../views/main.php");
        
    }else{
        header("Location: ../index.php");
    }
}