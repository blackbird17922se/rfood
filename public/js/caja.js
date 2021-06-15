$(document).ready(function(){
    var funcion;
    var totalS = 0;

    listarPedidosCaja()
    calcularVuelto()

    /* Lista los pedidos para el restaurante */
    function listarPedidosCaja(){
        funcion = 'listarPedidosCaja';

        $.post('../controllers/cajaController.php',{funcion},(response)=>{
            // console.log(response);

            const PEDIDOS = JSON.parse(response);
            let template = '';
            
            PEDIDOS.forEach(pedido=>{

                template+=`
        
                    <div idPedido="${pedido.idPedido}" idMesa="${pedido.idMesa}" class="col-2 col-sm-2 col-md-2 align-items-stretch">

                        <div class="card bg-light">
                            <div class="card-header text-muted border-bottom-0">Orden: ${pedido.idPedido}
                            
                            
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


    /* AL HACER CLICK EN EL BOTON DE TERMINADO */
    $(document).on('click','.selItem',(e)=>{
        funcion = 'cargarDatosPedido';
        console.log("cargarDatosPedido");
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('idPedido');
        const IDMESA = $(ELEM).attr('idMesa');
        console.log(ID);

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
    })


})