<?php
session_start();
if(!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 5)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper cnt-wrp-mdrn">

    <!-- Main content -->
    <section class="content">

        <div id="form_codbar" class="form-group">
            <label for="codbar">Código del Ítem</label>
            <input type="number" class="form-control" id="codbar" required>
        </div>

        <div class="form-group">
            <label for="cat_item">Categoría</label>
            <select id="cat_item" class="form-control select2" style="width: 100%;" required>
            </select>
        </div>

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" aria-describedby="nombreHelp" required>
        </div>

        <div class="form-group">
            <label for="pres_item">Presentación</label>
            <select id="pres_item" class="form-control select2" style="width: 100%;" required>
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
                <label for="tipo_ing">Categoría del Ingrediente</label>
                <select id="tipo_ing" class="form-control select2" style="width: 100%;" required>

                </select>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card card-mdrn">
                        <div class="card-header card-mdrn">
                            <ul class="nav nav-pills">
                        
                
                        </div>

                        <div class="card-body">
                            <div class="tab-content">


                                <!-- ENTRADAS -->
                                <div class="tab-pane active" id="entradas">
                                    <div class="card card-success card-mdrn">                              
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

                        <div class="container">
                            <h1>Ingredientes Asignados al Ítem</h1>
                            <a href="#" id="vaciar-carrito-ing" class="btn btn-danger btn-small">Vaciar Tabla</a>
                            <table class="table mt-4">
                                <thead class="table-success">
                                <tr>
                                    <th>id</th>
                                    <th>Nombre</th>
                                    <th>medida</th>
                                    <th>Cantidad</th>
                                    <th>Eliminar</th>
                                </tr>
                                </thead>
                                <tbody id="tbd-lista-ing"></tbody>
                            </table>

                        </div>

                        <div class="card-footer">
                            <!-- <a href="#" id="procesarItemMenu" class="btn btn-success btn-small">Guardar Ítem</a> -->
  
                            <button id="procesarItemMenu" class="btn btn-success">Guardar Ítem</button>
                            <button class="btn btn-danger salir">Salir</button>
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
<script src="../public/js/newItemMenu.js"></script>