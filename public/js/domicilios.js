$(document).ready(function(){

    var funcion = 0;
    const PEDIDOS_CTRL = '../controllers/pedidoController.php';
    const MESA_CTRLR = '../controllers/mesaController.php';
    const VENTA_CTRLR = "../controllers/ventaController.php";
    const CAJA_CONTROLLER = '../controllers/cajaController.php';



    listarDomiciliosCaja()
    /* Lista los domicilios */
    function listarDomiciliosCaja(){
        funcion = 10;

        $.post(CAJA_CONTROLLER,{funcion},(response)=>{
            console.log('list dom resp: ' + response);

            const PEDIDOS = JSON.parse(response);
            let template = '';
            
            PEDIDOS.forEach(pedido=>{

                template+=`
        
                    <div idDom="${pedido.idPedido}" class="col-12 col-sm-12 col-md-4 align-items-stretch">
                        <div class="card bg-dark-10">
                            <div class="card-header border-bottom-0">Orden: ${pedido.idPedido}</div>

                            <div class="card-body pt-0">    
                            <button class='verOrden btn btn-sm btn-primary' type="button" data-toggle="modal" data-target="#verOrden">
                                <i class='fas fa-plus-square mr-2'></i>Ver Orden
                            </button>
                            <button class='editarOrden btn btn-sm btn-warning'>
                                <i class='fas fa-plus-square mr-2'></i>Editar Orden
                            </button>

                            </div>                        
                        </div>
                    </div>
                `;   
            });
            $('#tb_domicios').html(template);
        });
    }


    /* AL HACER CLICK EN EL BOTON EDITAR ORDEN */
    $(document).on('click','.editarOrden',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('idDom');
        window.location.href ='edicionOrden.php' + "?idOrden=" + ID; 
    });

    /* AL HACER CLICK EN EL BOTON NUEVA ORDEN */
    $(document).on('click','.nuevaOrden',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('mesaId');
        console.log(ID);
        window.location.href ='nuevaOrden.php' + "?mesaId=" + ID; 
    });

    /* VER ITEMS DE LA ORDEN */
    $(document).on('click','.verOrden',(e)=>{
        
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const idOrden = $(ELEM).attr('idDom');
        // console.log(idOrden)
        funcion = 22

        /* Cargar las observaciones */
        $.post(PEDIDOS_CTRL,{funcion,idOrden},(response)=>{
            console.log('obser: ' + response);
            const encargados = JSON.parse(response);

            encargados.forEach(encargado=>{
                $('#observCli').html(encargado.observ);
            })
        });

        funcion = 21;
        
        // ajax
        $.post(PEDIDOS_CTRL,{idOrden,funcion},(response)=>{
            let registros = JSON.parse(response);
            let template ="";
            $('#registros').html(template);

            registros.forEach(registro => {
                template+=`
                <tr>
                    <td>${registro.det_cant}</td>
                    <td>${registro.nombprod}</td>
                    <td>${registro.presnom}</td>
                </tr>
                `;
                $('#registros').html(template);
                
            });
        })
    })

    /* NUEVO DOMICILIO */
    $(document).on('click','#nuevoDomicilio',(e)=>{
        window.location.href ='nuevaOrden.php' + "?mesaId=" + -1; 
    });


    // listarProdCons();
    // function listarProdCons(){
    //     funcion = "listarPedidos";
    //     $.post(PEDIDOS_CTRL,{funcion},(response)=>{
    //         // console.log(response);
   
    //     })
    // }

})
