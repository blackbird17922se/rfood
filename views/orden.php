<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>





  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">

    <div class="form-group">
        <label for="mesa">Mesa</label>
        <select id="mesa" class="form-control select2" style="width: 100%;" required>

        </select>
    </div>

    <div class="form-group">
        <label for="prod_tipo">Categoría del producto</label>
        <select id="prod_tipo" class="form-control select2" style="width: 100%;" required>

        </select>
    </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills">
                     
               
                    </div>

                    <div class="card-body">
                        <div class="tab-content">


                            <!-- ENTRADAS -->
                            <div class="tab-pane active" id="entradas">
                                <div class="card card-success">                              
                                    <div class="card-body p-0 table-responsive">
                                    <table id="tabla_products" class="display table table-hover text-nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Acción</th>
                                                <th>Codigo Producto</th>
                                                <th>Nombre</th>
                                                <th>Precio</th>
                                                <th>Ingredientes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
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
<script src="../public/js/orden.js"></script>
<!-- <script src="../public/js/carrito.js"></script> -->
