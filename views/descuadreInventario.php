<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper cnt-wrp-mdrn">

    <!-- SECCION CABECERA -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">    
          <div class="col-sm-12">
            <h1>Descuadre del Inventario</h1>
            <p>Ventas realizadas cuando uno o varios ingredientes tenían cero stock</p>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section>
        <div class="container-fluid">
            <div class="card card-danger card-mdrn">
              
                <div class="card-body p-0 table-responsive">
                  <table id="tb_descuadre" class="display table table-hover text-nowrap" style="width:100%">
                    <thead>

                      <tr>
                        <th>Codigo</th>
                        <th>Fecha</th>
                        <th>Codigo Venta</th>
                        <th>Ingrediente</th>
                        <th>Cantidad Descuadrada</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>              
                </div>

                <div class="card-footer">
                </div>
            </div>
        </div>
    </section>
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
<script src="../public/js/descuadreInventario.js"></script>
