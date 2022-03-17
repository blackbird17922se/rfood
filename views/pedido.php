<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2 || $_SESSION['rol']==4)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper cnt-wrp-mdrn">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">

        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestión de Pedidos</h1>
          </div>
        </div>
        <div class="row">
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- ********************** TABLA Pedidos ************************* -->
        <!-- Main content -->
        <section>
        <div class="container-fluid">
            <div class="card card-success card-mdrn">
                <div class="card-header">
                    <h3 class="card-title">Pedidos</h3>
                </div>

                <div class="card-body">
                    <div id="cb-pedidos" class="row d-flex align-items-stretch"></div>              
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
include_once "layouts/footer.php";

}else{
    session_destroy();
    header("Location: ../index.php");
}
?>
<script src="../public/js/datatables.js"></script>
<script src="../public/js/pedido.js"></script>