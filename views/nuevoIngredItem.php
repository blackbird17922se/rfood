<?php
session_start();
if(!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 5)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

<!-- Modal Editar Ingredientes -->
<div class="modal fade" id="edit-ingred" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Editar Ingrediente <span id="nomIngred"></span></h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    
                    <!-- alertas -->
                    <div class="alert alert-danger text-center" id="no_edit_ingred" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>Error al editar la cantidad</span>
                    </div>

                    <div class="alert alert-success text-center" id="edit_ingred" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Cantidad editada correctamente</span>
                    </div>
                    <form action="" id="form_edit_ingred">
                        <div class="form-group">
                            <label for="ingred_cant">Cantidad en <span id="nomMedida"></span></label>
                            <input type="text" class="form-control" id="ingred_cant" name="ingred_cant" aria-describedby="ingred_cantHelp" required>
                            <input type="hidden" id="id_edit_ingred">
                        </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn bg-gradient-primary float-right m-1">Guardar</button>
                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

        <input type="hidden" id="itemId" value="<?php echo $_GET['id']?>">


        <div class="container-fluid">
            <h1>Gestión Ingredientes <span id="nombre-item"></span></h1>

            <h3>Ingredientes Actuales</h3>
            <table class="table table-hover text-nowrap">
                <thead class="table-success">
                    <tr>
                        <th>Nombre Ingrediente</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-active" id="tb-ingreds-item">
                </tbody>
            </table>


            <h3>Asignación Ingredientes</h3>
            <div class="form-group">
                <label for="tipo_ing">Categoría del Ingrediente</label>
                <select id="tipo_ing" class="form-control select2" style="width: 100%;" required>

                </select>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card">
           
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

                        <div class="container">

                            <h3>Nuevos Ingredientes Asignados</h3>
                            <table class="table table-hover text-nowrap">
                                <thead class="table-success">
                                    <tr>
                                        <th>Nombre Ingrediente</th>
                                        <th>Cantidad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="table-active" id="tb-nIngr-Item">
                                </tbody>
                                
                            </table>
                            <a href="#" id="vaciar-carrito-ing" class="btn btn-danger btn-small">Vaciar Tabla</a>

                        </div>

                        <div class="card-footer">
                            <button id="procesarNIngredItem" class="btn btn-success">Guardar Ítem</button>
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
<script src="../public/js/nuevoIngredItem.js"></script>