<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">

        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestión de Pedidos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">gestión de Pedidos</li>
            </ol>
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
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Pedidos</h3>
                </div>

                <div class="card-body">
                    <table id="tabla_pedidos" class="display table table-hover text-nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <!-- <th>Categoría</th> -->
                                <th>Id Pedido</th>
                                <th>Mesa</th>
                                <th>Cantidad</th>
                                <!-- <th>Nombre</th> -->
                                <th>Id_prod</th>
                                <!-- <th>Presentacion</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>              
                </div>

                <div class="card-footer">
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
include_once "layouts/footer.php";

}else{
    // session_destroy();
    header("Location: ../index.php");
}
?>
<script src="../public/js/datatables.js"></script>
<script src="../public/js/pedido.js"></script>