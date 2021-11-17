<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

<!-- modal Ingredientes -->
<div class="modal fade" id="crearproduct" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Ingrediente</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">

                    <form action="" id="form-crear-product">


                        <div id="form_codbar" class="form-group">
                            <label id="labcodbar" for="codbar">Código de Barras</label>
                            <input type="number" class="form-control" id="codbar" required>
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre del Ingrediente</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" aria-describedby="nombreHelp" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prod_tipo">Tipo de Ingrediente</label>
                            <select id="prod_tipo" class="form-control select2" style="width: 100%;" required>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="medida">Medida del Ingrediente</label>
                            <select id="medida" class="form-control select2" style="width: 100%;" required>
                            </select>
                        </div>

                        <input type="hidden" id="idEditProd">


                        <!-- ALERTAS -->
                        <div class="alert alert-success text-center" id="add-product" style="display: none;">
                            <span><i class="fas fa-check m-1"></i>Registro Exitoso</span>
                        </div>

                        <div class="alert alert-danger text-center" id="noadd-product" style="display: none;">
                            <span><i class="fas fa-times m-1"></i>Ya existe el Ingrediente ingresado</span>
                        </div>

                        <div class="alert alert-success text-center" id="edit-product" style="display: none;">
                            <span><i class="fas fa-check m-1"></i>Ingrediente editado</span>
                        </div>


                </div>
                <div class="card-footer">
                    <button type="submit" class="btn bg-gradient-primary float-right m-1 autolote">Guardar</button>
                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal lote -->
<div class="modal fade" id="crearlote" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Crear lote</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">

                    <!-- ALERTAS -->
                    <div class="alert alert-success text-center" id="add-lote" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Registro Exitoso</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noadd-lote" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>Ya existe el lote ingresado</span>
                    </div>

                    <div class="alert alert-success text-center" id="edit-lote" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>lote editado</span>
                    </div>


                    <!-- FORMULARIO -->
                    <form id="form-crear-lote">

                        <div class="form-group">
                            <label for="nom_product_lote">Ingrediente: </label>
                            <label id="nom_product_lote"></label>
                        </div>

                        <div class="form-group">
                            <label for="lote_id_prov">Seleccione proveedor</label>
                            <select id="lote_id_prov" class="form-control select2" style="width: 100%;"></select>
                        </div>

                        <div class="form-group">
                            <label for="stock">Cantidad de <span id="med_Ingr_lote"></span> a ingresar en este lote</label>
                            <input type="text" class="form-control" id="stock" name="stock" aria-describedby="stockHelp" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="vencim">Fecha de vencimiento</label>
                            <input type="date" class="form-control" id="vencim" name="vencim" aria-describedby="vencimHelp">
                        </div>
    
                        <input type="hidden" id="lote_id_prod">

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
          <div class="col-sm-12">
            <h1>Gestión de Ingredientes</h1>
            <p>Gestiona desde aquí los ingredientes o elementos que contiene los  ítems del Menú o Carta</p>
          </div>

        </div>
        <div class="row">
            <button id="btn-crear" type="button" data-toggle="modal" data-target="#crearproduct" class="btn bg-gradient-primary ml-2" title="editar">Crear Ingrediente</button>
            <!-- <button id="btn-reporte" type="button" class="btn bg-gradient-success ml-2">Reporte Ingredientes</button> -->
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- ********************** TABLA Ingredientes ************************* -->
        <!-- Main content -->
        <section>
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Inventario de Ingredientes</h3>
                </div>

                <div class="card-body">
                    <table id="tb_ingreds" class="display table table-hover text-nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Acción</th>
                                <th>Nombre</th>
                                <th>Stock</th>
                                <th>Unidad</th>
                                <th>Tipo</th>
                                <th>Codigo Barras</th>
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
<script src="../public/js/ingred.js"></script>