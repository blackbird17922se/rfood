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
            <h1>Gestión de productos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">gestión de productos</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- ********************** TABLA PRODUCTS ************************* -->
        <!-- Main content -->
        <section>
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Inventario de productos</h3>
                </div>

<!-- funcion,id,codbar,nombre,compos,prod_tipo,prod_pres,precio,iva -->
                <div class="card-body">
                    <table id="tabla_products" class="display table table-hover text-nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Acción</th>
                                <th>Mesa</th>
                                <th>Nombre</th>
                                <th>Presentacion</th>
                                <th>idOrden</th>
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
<script src="../public/js/pedidoterm.js"></script>