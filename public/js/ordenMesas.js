$(document).ready(function(){

    var funcion = 0;
    var idMesero = 0;
    const PEDIDOS_CTRL = '../controllers/pedidoController.php';
    const USUARIO_CTRL = '../controllers/usuarioController.php';

    listarMeseros();
    cargarMesas();
    recuperarLSRecarga()
    ss()
    // console.log('mesero:' + idMesero);
    
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

    function cargarMesas(consulta){
        funcion = 18;
        
        // ajax
        $.post(PEDIDOS_CTRL,{consulta,funcion},(response)=>{
            console.log(response);
            const mesaS = JSON.parse(response);
            let templateMesa = '';
            let templateOrden = '';

            let tempButtons = '';
            
            mesaS.forEach(mesa=>{
                let tieneOrden = 0;
                let tempItemsOrden = '';
                // let tempProducts = '';
                // console.log(mesa.prods);
                let idOrden = 0;

                if (mesa.prods ==0) {
                    tieneOrden = 0;
                    tempItemsOrden+=`
                    <h2 class="lead"><b>Disponible</b></h2>
                    `;
                } else {

                    tieneOrden = 1;
                    
                    
                    mesa.prods.forEach(item=>{
                        console.log('item[0]'+item[0]);
                        idOrden=item[0]
                  
                        tempItemsOrden+=`
                        <h2 class="lead"><b>Orden Numero: ${item[0]} </b></h2>
                        `;
                    });
                }

                if (tieneOrden == 1) {
                    tempButtons=`

                        <button class='verOrden btn btn-sm btn-primary' type="button" data-toggle="modal" data-target="#verOrden">
                            <i class='fas fa-plus-square mr-2'></i>Ver Orden
                        </button>
                        <button class='editarOrden btn btn-sm btn-warning'>
                            <i class='fas fa-plus-square mr-2'></i>Editar Orden
                        </button>
                    `;
                    
                } else {
                    tempButtons=`
                        <button class='nuevaOrden btn btn-sm btn-primary'>
                            <i class='fas fa-plus-square mr-2'></i>Nueva Orden
                        </button>
                    `;
                }
// console.log(idOrden);

                // let idMesa = mesa.id_mesa;

                templateMesa+=`

                <div mesaId="${mesa.id_mesa}" idOrden="${idOrden}" mesaNom="${mesa.nomMesa}" class="col-12 col-sm-6 col-md-6 align-items-stretch">
                    <div class="card bg-dark-10">
                        <div class="card-header border-bottom-0">Mesa: ${mesa.nomMesa}
                        </div>
                
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-12">
                                    ${tempItemsOrden}
                                </div>                           
                            </div>
                            ${tempButtons}
                            
                        </div>                        
                    </div>
                </div>
                `;
            });
            $('#tb_Ordenmesas').html(templateMesa);
        })
    }

    /* AL HACER CLICK EN EL BOTON EDITAR ORDEN */
    $(document).on('click','.editarOrden',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('idOrden');
        if (idMesero == 0) {
            alert('elija un mesero')
        } else {
        window.location.href ='edicionOrden.php' + "?idOrden=" + ID; 
        }
    });

    /* AL HACER CLICK EN EL BOTON NUEVA ORDEN */
    $(document).on('click','.nuevaOrden',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('mesaId');
        console.log(ID);
        if (idMesero == 0) {
            alert('elija un mesero')
        } else {
            window.location.href ='nuevaOrden.php' + "?mesaId=" + ID; 
        }
    });

    /* VER ITEMS DE LA ORDEN */
    $(document).on('click','.verOrden',(e)=>{
        
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const idOrden = $(ELEM).attr('idOrden');
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
