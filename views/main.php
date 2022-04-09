<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2 || $_SESSION['rol']==4 || $_SESSION['rol'] == 5 || $_SESSION['rol'] == 3)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper cnt-wrp-mdrn">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">

        <div class="row mb-2">
<!--           <div class="col-sm-12">
            <h1>Bienvenid@ a RFood</h1>
            <h4>Seleccione una opcion del menu lateral para empezar</h4>
          </div> -->
          
        </div>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-1">
            <img src="../public/icons/appicon.ico" width="60px">
              
            </div>
            <div class="col-sm-10">
              
              <h1>RFood</h1>
              <p>DsD RFood para Windows 98</p>
              <p><i>RFood Milestone 2.3 - version de pruebas 2.3</i></p>
              <p>Copyright &copy; 2020-2022 DsD Desarrollos Dinámicos</p>
              <br>
              <p>Se autoriza el uso de este producto a:</p>
              <p><i>UnRapidin</i></p>
              <p><i>Id. Del producto: 32644-072-01808804</i></p>
              <br>
              <h4>Novedades</h4>
              <p>
                - Agrega venta del dia al momento de salir<br>
                - Agrega la sección descuadre de inventario (cuando se realizó una venta, pero un ingrediente estaba con 0 stock)<br>
                - Corrige el descuento de materia prima (Ingredientes) del inventario al vender un plato<br>
                - Puede crear un nuevo ingrediente sin asignarle un código de barras<br>
                - Permite editar la cantidad de X ingrediente del item.<br>
                - Permite borrar el ingrediente del item.<br>
              </p>

            </div>
          </div>

        </div>
      </div><!-- /.container-fluid -->
    </section>

  </div>
  <!-- /.content-wrapper -->
  <script>

var audio = new Audio('cc.mp3');
audio.play();
    </script>
<?php
include_once "layouts/footer.php";

}else{
    session_destroy();
    header("Location: ../index.php");
}
?>
<script src="../public/js/datatables.js"></script>
<script src="../public/js/pedido.js"></script>