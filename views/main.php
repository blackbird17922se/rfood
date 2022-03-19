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
          <div class="col-sm-12">
            <h1>Bienvenid@ a RFood</h1>
            <h4>Seleccione una opcion del menu lateral para empezar</h4>
          </div>
          
        </div>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-1">
              <img src="../public/img/dsdlogo.png" alt="">
              
            </div>
            <div class="col-sm-10">
              <p><i>RFood Milestone 2.0 - version de pruebas 2.0</i></p>

              <p>DsD RFood para Windows 95</p>
              <p>Copyright &copy; 2020-2022 DsD Desarrollos Dinámicos</p>
              <br>
              <p>Se autoriza el uso de este producto a:</p>
              <p><i>UnRapidin</i></p>
              <p><i>Id. Del producto: 32644-072-01808804</i></p>

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