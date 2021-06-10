<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

<div class="modal fade" id="crearlab" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Laboratorio</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-success text-center" id="add-lab" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Laboratorio Registrado</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noadd-lab" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>Ya existe ese laboratorio</span>
                    </div>

                    <div class="alert alert-success text-center" id="edit-lab" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Laboratorio Actualizado</span>
                    </div>

                    <form action="" id="form-crear-lab">
                        <div class="form-group">
                            <label for="nom_lab">Nombre del Laboratorio</label>
                            <input type="text" class="form-control" id="nom_lab" name="nom_lab" aria-describedby="nom_labHelp" required>
                            <input type="hidden" id="id_edit-lab">
                            <!-- <small id="nom_labHelp" class="form-text text-muted">ingrese nombre laboratorio.</small> -->
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

<!-- Modal categoria -->
<div class="modal fade" id="crear-categ" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">

                <div class="card-header">
                    <h3 class="card-title">Categoría del producto</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                
                <div class="card-body">

                    <!-- alertas -->
                    <div class="alert alert-success text-center" id="add-tipo" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Categoría registrada correctamente</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noadd-tipo" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>Ya está registrada ese tipo de Categoría</span>
                    </div>

                    <div class="alert alert-success text-center" id="edit-tipo" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Categoría editada correctamente</span>
                    </div>

                    <!-- formulario crear/editar -->
                    <form action="" id="form-crear-categ">
                        <div class="form-group">
                            <label for="nom_tipo">Categoría</label>
                            <input type="text" class="form-control" id="nom_tipo" name="nom_tipo" aria-describedby="nom_tipoHelp" required>
                            <input type="hidden" id="id_edit_tipo">
                            <small id="nom_tipoHelp" class="form-text text-muted">Ingrese el nombre de la categoría del producto o platillo.</small>
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

<!-- Modal present -->
<div class="modal fade" id="crearpresent" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Presentación</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <!-- alertas -->
                    <div class="alert alert-success text-center" id="add-present" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Presentación registrada correctamente</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noadd-present" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>Ya está registrada ese tipo de presentación</span>
                    </div>

                    <div class="alert alert-success text-center" id="edit-present" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Presentación editada correctamente</span>
                    </div>
                    <form action="" id="form-crear-present">
                        <div class="form-group">
                            <label for="nom_present">Nombre de la Presentación</label>
                            <input type="text" class="form-control" id="nom_present" name="nom_present" aria-describedby="nom_presentHelp" required>
                            <input type="hidden" id="id_edit_present">
                            <small id="nom_presentHelp" class="form-text text-muted">ingrese nombre presentación.</small>
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills">
                     
                            <li class="nav-item"><a href="#entradas" class="nav-link active" data-toggle="tab">Entradas</a></li>
                            <li class="nav-item"><a href="#hamburgs" class="nav-link" data-toggle="tab">Hamburguesas</a></li>
                            <li class="nav-item"><a href="#pizzas" class="nav-link" data-toggle="tab">Pizzas</a></li>
                            <li class="nav-item"><a href="#hotdogs" class="nav-link" data-toggle="tab">Perros C.</a></li>
                            <li class="nav-item"><a href="#tortillas" class="nav-link" data-toggle="tab">Tortillas</a></li>
                            <li class="nav-item"><a href="#lasagna" class="nav-link" data-toggle="tab">Lasagna</a></li>
                            <li class="nav-item"><a href="#paneck" class="nav-link" data-toggle="tab">Pane Cooks</a></li>
                            <li class="nav-item"><a href="#bebidas" class="nav-link" data-toggle="tab">Bebidas</a></li>
                            <li class="nav-item"><a href="#otros" class="nav-link" data-toggle="tab">otros</a></li>
           
                        </ul>
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
                                                        <th>Nombre</th>
                                                        <th>Nombre</th>
                                                       
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table> 

                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>

                            <!-- OPCION VEHICULOS -->
                            <div class="tab-pane" id="vehiculos">
                                <div class="card card-success">
                                    
                                    <div class="card-body p-0 table-responsive">
                                    <table id="tabla_veh" class="display table table-hover text-nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Matrícula</th>
                                                    <th>Marca</th>
                                                    <th>Denominación</th>
                                                    <th>Modelo</th>
                                                    <th>Color</th>
                                                    <th>Tipo de vehículo</th>
                                                    <th>Cliente</th>
                                                    <th>Pendientes</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>

                            <!-- panel laboraorio -->
                            <div class="tab-pane active" id="lab">
                                <div class="card card-success">
                                    
                                    <div class="card-body p-0 table-responsive">
                                        <table class="table table-hover text-nowrap">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Laboratorio</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-active" id="tbd-labs">

                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>

                            <!-- panel categoria -->
                            <div class="tab-pane" id="categ">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <div class="card-title">Buscar Categoría
                                            <button type="button" data-toggle="modal" data-target="#crear-categ" class="btn bg-gradient-primary btn-sm m-2">Crear Categoría</button></div>
                                        <div class="input-group">
                                            <input id="busq-tipo" type="text" class="form-control float-left" placeholder="Ingrese nombre">
                                            <div class="input-group-append">
                                                <buttom class="btn btn-default"><i class="fas fa-search"></i></buttom>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0 table-responsive">
                                        <table class="table table-hover text-nowrap">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Nombre Categoría</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-active" id="tbd-tipos">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>

                            <div class="tab-pane" id="present">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <div class="card-title">Buscar presentación<button type="button" data-toggle="modal" data-target="#crearpresent" class="btn bg-gradient-primary btn-sm m-2">Crear Presentacion</button></div>
                                        <div class="input-group">
                                            <input id="busq-present" type="text" class="form-control float-left" placeholder="Ingrese nombre">
                                            <div class="input-group-append">
                                                <buttom class="btn btn-default"><i class="fas fa-search"></i></buttom>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0 table-responsive">
                                        <table class="table table-hover text-nowrap">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Presentación</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-active" id="tbd-presents">
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
<script src="../public/js/laboratorio.js"></script>
<script src="../public/js/tipo.js"></script>
<script src="../public/js/presentacion.js"></script>