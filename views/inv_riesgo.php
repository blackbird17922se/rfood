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
          
          <div class="col-sm-6">
            <h1>Lotes En Riesgo</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Lotes En Riesgo</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section>
        <div class="container-fluid">
            <div class="card card-danger card-mdrn">
                <div class="card-header">
                    <h3 class="card-title">Lotes en riesgo</h3>    
                </div>

                <div class="card-body p-0 table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Codigo<br>Lote</th>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Proveedor</th>
                        <th>Meses<br>Restantes</th>
                        <th>Dias<br>Restantes</th>
                      </tr>
                    </thead>

                    <tbody id="tbd-lotes" class="table-active">
                      
                    </tbody>
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
<script src="../js/riesgo.js"></script>
