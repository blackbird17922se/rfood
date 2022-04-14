$(document).ready(function(){
    var funcion;
    var totalS = 0;
    var idOrdenSel=0;
    var respStock = 2;

    const CAJA_CONTROLLER = '../controllers/cajaController.php';

    listarPedidosCaja()
    calcularVuelto()

    /* Lista los pedidos para el restaurante */
    function listarPedidosCaja(){
        funcion = 'listarPedidosCaja';

        $.post('../controllers/cajaController.php',{funcion},(response)=>{
            console.log(response);

            const PEDIDOS = JSON.parse(response);
            let template = '';
            
            PEDIDOS.forEach(pedido=>{

                template+=`
        
                    <div idPedido="${pedido.idPedido}" idMesa="${pedido.idMesa}" class="col-2 col-sm-2 col-md-2 align-items-stretch">

                        <div class="card bg-dark-10">
                            <div class="card-header border-bottom-0">Orden: ${pedido.idPedido}
                            
                            
                            </div>

                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12">
                                
                                    <h2 class="lead"><b>Mesa: ${pedido.idMesa}</b></h2>

                                    </div>                           
                                </div>

                                <button class='selItem btn btn-sm btn-primary'>
                                    <i class='fas fa-plus-square mr-2'></i>Seleccionar
                                </button>

                            </div>                        
                        </div>
                    </div>
                `;   
            });
            $('#cb-mesas').html(template);
        });
    }


    /* Cargar los datos y costos de ese pedido */
    $(document).off('click','.selItem').on('click','.selItem',(e)=>{
        funcion = 'cargarDatosPedido';
        console.log("cargarDatosPedido");
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('idPedido');
        const IDMESA = $(ELEM).attr('idMesa');
        idOrdenSel = ID;
        // console.log(ID);

        $.post('../controllers/cajaController.php',{funcion,ID,IDMESA},(response)=>{
            // console.log(response);

            const PEDIDOS = JSON.parse(response);
            let templateS = '';
            let total = 0;
          
            
            PEDIDOS.forEach(pedido=>{

                templateS+=`${pedido.template}'`;

                total += pedido.subtotal

                totalS = total;
                $('#total').html(total.toFixed(0));
            });
            $('#lista-compra').html(templateS);


            // listarPedidos()
        })

    });

    function calcularVuelto(){
        let vuelto = 0;
        let pago = 0;
        
        pago = $('#pago').val();
        
        vuelto = pago - totalS;
                
        $('#vuelto').html(vuelto);
    }



    $('#pago').keyup((e)=>{
        calcularVuelto()
    });

    /* Vaciar la tabla y demas campos */
    function vaciarTabla(){
        $('#lista-compra').empty();
        $('#pago').val('');
        // eliminarLS();
        // contarProductos();
        // calcularTotal();
        // datatable.ajax.reload();
    }


    $(document).on('click','#procesar-compra',(e)=>{
        procesarCompra();
    })

    /* ´------------------ */
    function procesarCompra(){
    var respuesta = 1;

        funcion = 'registrarVenta';

        let idOrdSel = idOrdenSel;
        let total = $('#total').get(0).textContent;


        //console.log("ord selec para consulta stock: " + idOrdenSel);

        $.post('../controllers/cajaController.php',{funcion,total,idOrdSel,formaPago},(response)=>{
            console.log("Ha respondido: "+response);
            // respStock = response;
            respuesta = response;
        
           // console.log("Respuesta a enviar: " + respuesta);

            if(respuesta == 0){

                registrarVenta();

                /* Implementacion recibo */

                Swal.fire({
                    title: 'Venta Realizada',
                    text: "¿Desea imprimir recibo?",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Imprimir',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {

                        let funcion = "ultimaVenta";
                        $.post('../controllers/cajaController.php',{funcion},(response)=>{
                            console.log(response);
                        

                            $.ajax({
                                url: 'ticket.php',
                                type: 'POST',
                                success: function(resp){
                                    if(resp==1){
                                        alert('imprime..');
                                            vaciarTabla();
                                        // location.href = '../views/caja.php'
                                    }else{
                                        alert('error..');
                                        vaciarTabla()
                                        // location.href = '../views/caja.php'
                                    }
                                }
                            })                   
                        });

            
                        console.log("selecciono imprimir");
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        console.log("selecciono no imprimir");

                        $.ajax({
                            url: 'ticketc.php',
                            type: 'POST',
                            success: function(resp){
                                if(resp==1){
                                    alert('abre..');
                                        vaciarTabla();
                            // location.href = '../views/caja.php'

                                }else{
                                    // alert('error..');
                                    vaciarTabla()
                                    // location.href = '../views/caja.php'

                                }
                            }
                        })


                        vaciarTabla()
                        // location.href = '../views/caja.php'
                    }
                });
                /* END */
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Atencion',
                    text: 'Escasea materia prima en algun plato',
                })
            }
        // });  //Cierre then
    });

    }



    /* Radio buttons de forma de pago. la opcion por default es efectivo (0) */
    var formaPago = $("input[name='fpago']:checked").val();
    console.log(formaPago);


    $("input[name='fpago']").click(function () {    
        // alert("La edad seleccionada es: " + $('input:radio[name=fpago]:checked').val());
        // // alert("La edad seleccionada es: " + $(this).val());
        formaPago =$('input:radio[name=fpago]:checked').val()
        console.log(formaPago);
    });



    function registrarVenta(){
      
        funcion = 'registrarVenta';

        let total = $('#total').get(0).textContent;
        let idOrdSel = idOrdenSel;

        console.log("ord sel: " + idOrdenSel);

        $.post('../controllers/cajaController.php',{funcion,total,idOrdSel,formaPago},(response)=>{
            console.log(response);

            // Modificar estado del pedido
            funcion = 'pagado';
            console.log("pagado");
    
            $.post('../controllers/pedidoController.php',{funcion,idOrdenSel},(response)=>{
                console.log(response);
                idOrdenSel=0;
                listarPedidosCaja() 
            })
        })
    }



    function verificarStock(){
      console.log('verificarStock ejecutada');
        funcion = 'verificarStock';

        let idOrdSel = idOrdenSel;

        console.log("ord selec para consulta stock: " + idOrdenSel);

        $.post(CAJA_CONTROLLER,{funcion,idOrdSel},(response)=>{
            console.log("Ha respondido: "+response);
            respStock = response;
        });
    }

});
 
