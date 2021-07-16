<?php
session_start();
if(!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 5)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

        <div id="form_codbar" class="form-group">
            <label id="labcodbar" for="codbar">Código de producto</label>
            <input type="number" class="form-control" id="codbar" required>
        </div>

        <div class="form-group">
            <label for="prod_tipo">Categoría del producto</label>
            <select id="prod_tipo" class="form-control select2" style="width: 100%;" required>
            </select>
        </div>

        <div class="form-group">
            <label for="nombre">Nombre del producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" aria-describedby="nombreHelp" required>
        </div>

        <div class="form-group">
            <label for="prod_pres">Presentación del producto</label>
            <select id="prod_pres" class="form-control select2" style="width: 100%;" required>
            </select>
        </div>

        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" step="any" class="form-control" id="precio" name="precio" aria-describedby="precioHelp" required>
        </div>

        <div class="form-group col-md-12">
            <label >APLICAR IVA</label><br>
            <div class="col-md-6">
                <input type="checkbox" id="iva" class="form-check-input">
            </div>
            <small id="tipo_servHelp" class="form-text text-muted">Seleccione si aplica iva</small>
        </div>

        <input type="hidden" id="id_edit-prod">

        <div class="container-fluid">
            <h1>Asignar ingredientes</h1>


            <div class="form-group">
                <label for="tipo_ing">Categoría del producto</label>
                <select id="tipo_ing" class="form-control select2" style="width: 100%;" required>

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
                                                    <th>Cantidad</th>
                                                    <th>Medida</th>
                                                    <th>Nombre</th>
                                                    <th>id producto</th>
                    
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
                            <li id=""  class="nav-item">
                                <a href="#" class="btn btn-warning btn-block" id="procesarProd">Crear pp</a>
                            </li>
                            <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
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
    session_destroy();
    header("Location: ../index.php");
}
?>

<script src="../public/js/datatables.js"></script>
<script src="../public/js/newProduct.js"></script>