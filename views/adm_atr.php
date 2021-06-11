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

<!-- Modal Mesas -->
<div class="modal fade" id="crearmesa" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Mesa</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <!-- alertas -->
                    <div class="alert alert-success text-center" id="add-mesa" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Mesa registrada correctamente</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noadd-mesa" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>Ya está registrada esa Mesa</span>
                    </div>

                    <div class="alert alert-success text-center" id="edit-mesa" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Mesa editada correctamente</span>
                    </div>
                    <form action="" id="form-crear-mesa">
                        <div class="form-group">
                            <label for="nom_mesa">Identificación de la Mesa</label>
                            <input type="text" class="form-control" id="nom_mesa" name="nom_mesa" aria-describedby="nom_mesaHelp" required>
                            <input type="hidden" id="id_edit_mesa">
                            <small id="nom_mesaHelp" class="form-text text-muted">ingrese un numero o codigo de la Mesa.</small>
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestion Atributos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Gestion Atributos</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a href="#categ" class="nav-link active" data-toggle="tab">Categorías de productos</a></li>
                            <li class="nav-item"><a href="#present" class="nav-link" data-toggle="tab">Presentación del Producto</a></li>
                            <li class="nav-item"><a href="#mesas" class="nav-link" data-toggle="tab">Mesas</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- .tab-content>(.tab-pane>.card.card-success>(.card-header>.card-title)+(.card-body)+(.card-footer)) -->
                        <div class="tab-content">

                            <!-- panel categoria -->
                            <div class="tab-pane active" id="categ">
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
                            
                            <!-- Presentacion del producto -->
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

                            <!-- Mesas -->
                            <div class="tab-pane" id="mesas">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <div class="card-title">Buscar mesa
                                        <button type="button" data-toggle="modal" data-target="#crearmesa" class="btn bg-gradient-primary btn-sm m-2">Crear Mesa</button></div>
                                        <div class="input-group">
                                            <input id="busq-mesa" type="text" class="form-control float-left" placeholder="Ingrese nombre">
                                            <div class="input-group-append">
                                                <buttom class="btn btn-default"><i class="fas fa-search"></i></buttom>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0 table-responsive">
                                        <table class="table table-hover text-nowrap">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Numero de Mesa</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-active" id="tbd-mesas">
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
<script src="../public/js/tipo.js"></script>
<script src="../public/js/presentacion.js"></script>
<script src="../public/js/mesa.js"></script>