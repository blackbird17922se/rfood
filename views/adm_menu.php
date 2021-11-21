<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

<!-- modal producto -->
<div class="modal fade" id="crearproduct" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Producto</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">

                    <form action="" id="form-crear-product">

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
                            <label for="compos">Ingredientes</label>
                            <input type="text" class="form-control" id="compos" name="compos" aria-describedby="composHelp">
                            <small id="composHelp" class="form-text text-muted">Ingrese los ingredientes del producto o platillo.</small>
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


                        <!-- ALERTAS -->
                        <div class="alert alert-success text-center" id="add-product" style="display: none;">
                            <span><i class="fas fa-check m-1"></i>Registro Exitoso</span>
                        </div>

                        <div class="alert alert-danger text-center" id="noadd-product" style="display: none;">
                            <span><i class="fas fa-times m-1"></i>Ya existe el producto ingresado</span>
                        </div>

                        <div class="alert alert-success text-center" id="edit-product" style="display: none;">
                            <span><i class="fas fa-check m-1"></i>Producto editado</span>
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
                            <label for="nom_product_lote">Producto:</label>
                            <label id="nom_product_lote">NombreX</label>
                        </div>

                        <div class="form-group">
                            <label for="lote_id_prov">Seleccione proveedor</label>
                            <select id="lote_id_prov" class="form-control select2" style="width: 100%;"></select>
                        </div>

                        <div class="form-group">
                            <label for="stock">Stock</label>
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
          <div class="col-sm-6">
            <h1>Gestionar Menú y Carta</h1>
          </div>
         
        </div>
        <div class="row">
            <!-- <button id="btn-crear" type="button" data-toggle="modal" data-target="#crearproduct" class="btn bg-gradient-primary ml-2" title="editar">Crear Producto</button>
            <a href="newProduct.php">Nuevo PP</a> -->
            <button class="ingreds btn btn-sm btn-warning"><a href="newItemMenu.php">Nuevo Item o Platillo</a></button>

        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- ********************** MENU ************************* -->
        <!-- Main content -->
        <section>
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Platillos y Bebidas</h3>
                </div>
                <div class="card-body">
                    <table id="tabla_products" class="display table table-hover text-nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Acción</th>
                                <th>Codigo Producto</th>
                                <th>Categoría</th>
                                <th>Nombre</th>
                                <th>Presentacion</th>
                                <th>Precio</th>
                                <th>Ingredientes</th>
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
<script src="../public/js/menu.js"></script>