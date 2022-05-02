<?php
session_start();
if(!empty($_SESSION['rol'])){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper cnt-wrp-mdrn">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">

        <div class="row mb-2">
<!--           <div class="col-sm-12">
            <h1>Bienvenid@ a RFood</h1>
            <h4>Seleccione una opcion del menu lateral para empezar</h4>
          </div> -->
          
        </div>
        <div class="container-fluid">
          <div class="row">
<!--             <div class="col-sm-1">
            <img src="../public/icons/appicon.ico" width="60px">
              
            </div> -->
            <div class="col-sm-10">
              
              <h1>RFood</h1>
              <p>DsD RFood para Windows Server 2003</p>
              <!-- Num version princip,Numero cuando hay nuevos cambios,Año,mes,dia,hora -->
              <p><i><b>RFood Milestone 4</b> <br> Version de pruebas.Build 4.3.2.220501-</i></p>
              <p>Copyright &copy; 2020-2022 DsD Desarrollos Dinámicos</p>
              <br>
              <p>Se autoriza el uso de este producto a:</p>
              <p><i>UnRapidin</i></p>
              <p><i>Id. Del producto: 32644-072-01808804</i></p>
              <br>
              <h4>Novedades</h4>
              <h5>01/05/2022 (Build 4.3.2.220501-)</h5>
              <p>
                * Permite ver las ventas del dia que al usuario seleccione. <br>
                * Incorpora ventas por dia y generales. <br>
              </p>
              

              <h4>Anteriores Mejoras</h4>
              <h5>17/04/2022 (Build 4.2.220417-)</h5>
              <p>
                * Corrige la falla que no permitía ver las ventas del día. <br>
                * Incorpora varias mejoras visuales en etiquetas de campos, botones y formularios. <br>
                * Incorpora una alerta que se despliega al no seleccionar un mesero de la lista al crear/editar una orden. <br>
                * Mejoras de rendimiento.
              </p>

              <h5>14/04/2022 (Build 4.1.220414-0000)</h5>
              <p>
                - Mejora la parte visual y el manejo en Caja <br>
                - Se añadio el modulo domicilio <br>
                - Agrega seleccion de Forma de pago al momento de cancelar. <br>
                - Incluye la seleccion del mesero al momento de crear una nueva orden o domicilio <br>
                - El modulo de proveedores esta activado para gestionar proveedores de materia prima. <br>
                - Multiples mejoras graficas. <br>
                - Corrige la falla que no permitia acceder a los usuarios tipo mesero a los modulos de orden. <br>
                - Permite ver el medio de pago al ver en detalle una venta.
                - Agrega nuevo diseño en modulos de orden y ventas. ahora se puede generar <br>
                  las ventas al visualizar todas las mesas registradas, cuando la mesa este <br>
                  disponible, se dispone del boton 'Nueva orden' para crear una nueva orden a la mesa. <br>
                  Cuando la mesa esta con una orden, se visualiza el id de la orden y dos botones <br>
                  Uno para ver los productos de dicha orden y otro para editar esa orden. <br>
                  <br>
                  - El modulo de caja permite ver las mesas que tienen pedidos pendientes por cancelar,<br>
                  solo se debe presionar el boton 'seleccionar' y cargara los items y costos a cobrar de <br>
                  dicha mesa.
              </p>

              <h5>08/04/2022 (Build 2.4.220408-0000)</h5>
              <p>
                - Optimizacion interna del sistema. <br>
                - Agrega el modulo para gestionar las mesas <br>
                - El sistema muestra el alias o numero asignado a la mesa, no su id. <br>
                - Eliminacion de Elementos inecesarios <br>
                - Correcciones en la parte visual desde dispositivos moviles en el cuadro de procesar orden y caja <br>
                  &nbsp &nbsp al arreglar algunos titulos, textos y diseño.
              </p>

              <p>
                - Agrega venta del dia al momento de salir<br>
                - Agrega la sección descuadre de inventario (cuando se realizó una venta, pero un ingrediente estaba con 0 stock)<br>
                - Corrige el descuento de materia prima (Ingredientes) del inventario al vender un plato<br>
                - Puede crear un nuevo ingrediente sin asignarle un código de barras<br>
                - Permite editar la cantidad de X ingrediente del item.<br>
                - Permite borrar el ingrediente del item.<br>
              </p>

            </div>
          </div>

        </div>
      </div><!-- /.container-fluid -->
    </section>

  </div>
  <!-- /.content-wrapper -->
  <script>
    var audio = new Audio('cc.mp3');
    audio.volume = 0.08;
    audio.play();
  </script>
<?php
include_once "layouts/footer.php";

}else{
    session_destroy();
    header("Location: ../index.php");
}
?>
<script src="../js/datatables.js"></script>
<!-- <script src="../js/pedido.js"></script> -->