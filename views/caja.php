<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <section>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                    gjhgjgjh
                    </div>

                    <div class="card-body">
                        <div id="cb-mesas" class="row d-flex align-items-stretch"></div>              
                    </div>

                    <div class="card-body p-0">
                    
                        <button id="actualizar"class="btn btn-success">Actualizar</button>
                        <div id="cp"class="card-body p-0">
                        <div class="card-body">
                        <div id="cb-pedidos" class="row d-flex align-items-stretch"></div>


                        <div>
                        <table class="compra table table-hover text-nowrap">
                                <thead class='table-success'>
                                    <tr>
                                        <th scope="col">Mesa</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Presentación</th>                                     
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Precio Unid.</th>
                                        <th scope="col">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody id="lista-compra" class='table-active'>
                                    
                                </tbody>
                            </table>
                        </div>             
                    </div>


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

                                


                            </div>
                        </div>
                        <div class="row justify-content-between">
                           
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

<script src="../public/js/caja.js"></script>