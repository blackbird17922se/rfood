<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  <!-- Por su poca funcionalidad se anulo el header -->
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Compra</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Compra</li>
            </ol>
          </div>
        </div>
      </div>
    </section> -->

    <!-- Main content -->

    <section>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                    </div>
                    <div class="card-body p-0">
                        <header>
                            <!-- <div class="logo_cp">
                                <img src="../img/logo.png" width="100" height="100">
                            </div> -->
                            <h1 class="titulo_cp">NUEVA VENTA</h1>
                            <div class="datos_cp">
                                <small>(Datos Opcionales)</small>
                                <div class="form-group row">
                                    <span>Cliente: </span>
                                    <div class="input-group-append col-md-6">
                                        <input type="text" class="form-control" id="cliente" placeholder="Ingresa nombre">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span>Idenificación: </span>
                                    <div class="input-group-append col-md-6">
                                        <input type="number" class="form-control" id="dni" placeholder="Ingresar número de identificación del cliente">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span>Vendedor: </span>
                                    <h3>
                                        <?php
                                            echo $_SESSION['nom'];
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </header>
                        <button id="actualizar"class="btn btn-success">Actualizar</button>
                        <div id="cp"class="card-body p-0">
                            <table class="compra table table-hover text-nowrap">
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
                                            <div class="info-box mb-3 bg-info">
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
                        <div class="row justify-content-between">
                            <div class="col-md-4 mb-2">
                                <a href="../views/adm_cat.php" class="btn btn-primary btn-block">Agregar mas productos</a>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <a href="#" class="btn btn-success btn-block" id="procesar-compra">Realizar venta</a>
                            </div>
                        </div>
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

<script src="../public/js/carrito.js"></script>