<?php
session_start();
if (!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 3)) {
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

    <!-- modal items en orden de la mesa -->
    <div class="modal fade" id="verOrdenCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    
    <input type="hidden" id="idMesaSelect" value="">    
    <input type="hidden" id="idOrdenSelect" value="">  

    <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

                <div class="card card-mdrn">
                    <div class="card-header">
                        <div class="card-title"><span id="tituloDetalle"></span></div>

                        <button data-dismiss="modal" aria-label="close" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <div class="card-body table-responsive">

                        <div class="row">

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
                                                <span class="info-box-number" id="vuelto"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-4 col-sm-12">

                                <div class="form-group">
                                    <label for="formaPago">Forma de Pago</label>
                                    <select id="formaPago" class="form-control select2" style="width: 100%;" required>
                                        <option value=""></option>
                                        <option value="1">Efectivo</option>
                                        <option value="2">Tarjeta</option>
                                        <option value="3">Nequi</option>
                                        <option value="4">Daviplata</option>
                                    </select>
                                </div>

                            </div>


                            <div class="col-md-4 col-sm-12">
                                <a href="#" class="btn btn-success" id="procesar-compra">Realizar venta</a>
                            </div>

                        </div>

                        <table id="tb-items-orden" class="caja table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Presentación</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Precio Unid.</th>
                                    <th scope="col">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody id="lista-compra" class='table-active'></tbody>
                        </table>

                    </div>
                    <div class="card-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper cnt-wrp-mdrn">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="row">
                            <img src="../public/icons/calculator_32.png" title="caja">
                            <h1 class="ml-2">Caja</h1>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- ********************** TABLA Pedidos ************************* -->
        <!-- Main content -->


        <!-- INTEGRA TARJETAS -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12 col-sm-6">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Mesas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Domicilios</a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                                    <section>
                                        <div class="container-fluid">
                                            <div class="card card-success card-mdrn">
                                                <div class="card-body">
                                                    <div id="tb_Ordenmesas" class="row d-flex align-items-stretch"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                    <section>
                                        <div class="container-fluid">
                                            <div class="card card-success card-mdrn">
                                                <div class="card-body">
                                                    <div id="tb_domicios" class="row d-flex align-items-stretch"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

            </div>
        </div>


        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


<?php
    include_once "layouts/footer.php";
} else {
    session_destroy();
    header("Location: ../index.php");
}
?>
<script src="../js/datatables.js"></script>
<script src="../js/cajaNueva.js"></script>
<script>
    $('#formaPago').select2({
        dropdownParent: $('#verOrdenCaja')
    });
</script>