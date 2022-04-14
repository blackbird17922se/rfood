$(document).ready(function(){

    var funcion = 0;
    const PEDIDOS_CTRL = '../controllers/pedidoController.php';
    const CAJA_CONTROLLER = '../controllers/cajaController.php';
    const USUARIO_CTRL = '../controllers/usuarioController.php';

    listarMeseros();
    listarDomiciliosCaja();
    recuperarLSRecarga();
    ss();

    $(".select2").select2({
        placeholder: "Seleccione una opcion",
    });


    function listarMeseros(){

        funcion = 8;
        $.post(USUARIO_CTRL,{funcion},(response)=>{
            console.log(response);
            const MESEROS = JSON.parse(response);
            let template = '';
            MESEROS.forEach(mesero => {
                template += `
                    <option value=""></option>
                    <option nomMesero=${mesero.nombres} value="${mesero.idMesero}">${mesero.nombres}</option>
                `;
            });
            $('#mesero').html(template);
            ss()
            // $('#mesero').val(12).trigger('change');
            // $('#mesero').val(12)
        })
    }

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

    /* EVENTO CUANDO SE CAMBIA EL MESERO Y ESTE SE GUARDA AL LOCALSTORAGE */
    $( "#mesero" ).change(function() {  
        eliminarLS();
        console.log('mesero');
        idMesero = $('#mesero').val();
        agregarLS(idMesero)
    });
    
    function ss(){
        console.log('sss');
        return $('#mesero').val(idMesero).trigger('change');
    }

    /* AGREGAR USUARIO al LOCAL STORAGE */
    function agregarLS(idMesero){
        let mesero = recuperarLS();
        mesero.push(idMesero);
        localStorage.setItem('LSMesero',JSON.stringify(mesero));
    }

    function recuperarLS(){
        let mesero;
        if(localStorage.getItem('LSMesero')===null){
            mesero=[];
        }else{
            mesero = JSON.parse(localStorage.getItem('LSMesero'));
        }
        return mesero;
    }

    function eliminarLS(){
        localStorage.removeItem('LSMesero');
    }

    /* Recupera el localstarage al recargar la pagina */
    function recuperarLSRecarga(){
        let mesero;
        if(localStorage.getItem('LSMesero')===null){
            mesero=[];
        }else{

            mesero = JSON.parse(localStorage.getItem('LSMesero'));
            mesero.forEach(mesro => {
                idMesero = mesro;

            });

        }
        $('#mesero').val(idMesero).trigger('change');
        return mesero;
    }




    // listarProdCons();
    // function listarProdCons(){
    //     funcion = "listarPedidos";
    //     $.post(PEDIDOS_CTRL,{funcion},(response)=>{
    //         // console.log(response);
   
    //     })
    // }

})
