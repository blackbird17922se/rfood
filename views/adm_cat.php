<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->




    <!-- SECCION CODIGO DE BARRAS VENTA -->
    <section>
      <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Añadir producto al carrito</h3>    
            </div>
            <div class="card-body">
              <!-- CODBAR -->
              <div class="input-group">
                <input id="campo_codbar" type="text" class="form-control float-left" placeholder="Ingrese aquí el código de barras del producto" autofocus>
              </div>
              <small class="form-text text-muted">Ingrese el código de barras del producto y luego presione la tecla "Enter" para añadir al carrito.</small>

            </div>
            <div class="card-footer"></div>
          </div>
        </div>
    </section>


    <!-- Carr -->
    <!-- <div class="container mb-5">
      <a href="#" id="procesar-pedido" class="btn btn-danger btn-block">Procesar compra</a>
      <table class="carro table table-over text-nowrap p-0">
        <thead class="table-success">
          <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Concentración</th>
            <th>Adicional</th>            
            <th>Precio</th>
            <th>Eliminar</th>

          </tr>
        </thead>
        <tbody id="tbd-lista"></tbody>
      </table>
    </div> -->
      <!-- <a href="#" id="vaciar-carrito" class="btn btn-primary btn-block">Vaciar Carrito</a> -->
    <!-- fin carr -->


    <!-- INTEGRADA -->
                  <input type="hidden" class="form-control" id="cliente" placeholder="Ingresa nombre">
                  <input type="hidden" class="form-control" id="dni" placeholder="Ingresar número de identificación del cliente">
    
    <section>




      <div id="cp"class="card-body p-0">
        <table  class="compra table table-hover text-nowrap">
            <thead class='table-success'>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Precio Total</th>
                    <th scope="col">Concentracion</th>
                    <th scope="col">Base</th>
                    <th scope="col">IVA</th>
                    <th scope="col">Laboratorio</th>
                    <th scope="col">Presentacion</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Sub Total</th>
                    <th scope="col">Eliminar</th>
                </tr>
            </thead>
            <tbody id="lista-compra" class='table-active'>
                
            </tbody>
        </table>


        <div class="row mt-4">

            <div class="col-md-4">
                <div class="card card-default">                                      
                    <div class="card-body">
                        <div class="info-box mb-3 bg-danger">
                        <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-left">TOTAL</span>
                                <span class="info-box-number" id="total"></span>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-body">
                        <div class="info-box mb-3 bg-success">
                            <span class="info-box-icon"><i class="fas fa-money-bill-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-left ">INGRESO</span>
                                <input type="number" id="pago" min="1" placeholder="Ingresa Dinero" class="form-control">
                            </div>
                        </div>                                       
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-body">
                        <div class="info-box mb-3 bg-info">
                            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-left ">VUELTO</span>
                                <span class="info-box-number" id="vuelto">3</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row justify-content-between">
     
          <div class="col-xs-12 col-md-12 ml-4 mb-3">
              <a href="#" class="btn btn-success btn-block" id="procesar-compra">Realizar venta</a>
          </div>
      </div>



            <div class="col-md-12">
                <div class="card card-default">

                    <div class="card-header">
                        <h3 class="card-title">
                        <i class="fas fa-cash-register"></i>
                        RESUMEN DE IMPUESTOS
                        </h3>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">TIPO</th>
                                <th scope="col">TOTAL</th>
                                <th scope="col">BASE</th>
                                <th scope="col">VALOR IVA</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">Exento</th>
                                <td class="ExentoIva"></td>
                                <td class="ExentoIva"></td>
                                <td class="ivaTotEx"></td>
                            </tr>
                            <tr>
                                <th scope="row">IVA</th>
                                <td id="subProdIva"></td>
                                <td id="subBaseProdIva"></td>
                                <td class="ivaTot"></td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td ></td>
                                <td id="baseTotal"></td>
                                <td class="ivaTot"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-body"></div>
                </div>
            </div>


            



        </div>
      </div>


    </section>
    <!-- FIN INTEGRADA -->





    <!-- Main content -->
    <section>
      <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Productos</h3>
            </div>

            <div class="card-body">
                <p>Has clic en Agregar para agregar el producto a la venta</p>
                <table id="tablaProdCat" class="display table table-hover text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Acción</th>
                            <th>Stock</th>
                            <th>Nombre</th>
                            <th>Composicion</th>
                            <th><i class="fas fa-lg fa-dollar-sign mr-1"></i>Precio</th>
                            <th>Laboratorio</th>
                            <th>Codigo Bar.</th>
          
                        </tr>
                    </thead>
                    <tbody>
                    <!--
                      Importante: el dataTable encargado de cargar el contenido
                      de este tbody se encuentra en carrito.js
                    -->
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

<script>
// cargarTabla();
// function cargarTabla(){
//   $.get("#cp","",function(respuesta){
//     console.log(respuesta);
//     $('#cp').html(respuesta);
//   });
// }


</script>

<script src="../public/js/catalogo.js"></script>
<script src="../public/js/carrito.js"></script>
