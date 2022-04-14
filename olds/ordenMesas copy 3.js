$(document).ready(function(){

    var funcion = 0;
    const PEDIDOS_CTRL = '../controllers/pedidoController.php';
    const MESA_CTRLR = '../controllers/mesaController.php';
    const VENTA_CTRLR = "../controllers/ventaController.php";


    cargarMesas();

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


    // listarPedidos()

    /* Lista los pedidos para el restaurante */
    // function listarPedidos(){
    //     funcion = 2;

    //     $.post(PEDIDOS_CTRL,{funcion},(response)=>{
    //         console.log(response);

    //         let templateS = '';
    //         const PEDIDOS = JSON.parse(response);
            
    //         PEDIDOS.forEach(pedido=>{

    //             pedido.observ = pedido.observ == null ? "Ninguna":pedido.observ;

    //             let tempProducts = '';
    //             pedido.prods.forEach(item=>{

    //                 /* 
    //                  * item[0] = nombre del platillo
    //                  * item[1] = presentacion del platillo
    //                  * item[2] = cantidad del platillo
    //                 */
    //                 tempProducts+=`
    //                 <h2 class="lead"><span class="card-num-big">${item[2]} </span><b>${item[0]}</b> ${item[1]}</h2>
    //                 `;
              
    //             });


    //             templateS+=`
        
    //             <div idPedido="${pedido.idPedido}" class="col-12 col-sm-6 col-md-6 align-items-stretch">
    //                 <div class="card bg-dark-10">
    //                     <div class="card-header border-bottom-0">Orden Numero: ${pedido.idPedido}
    //                         <h2 class="lead"><b>Mesa: ${pedido.nomMesa}</b></h2>
    //                     </div>
                
    //                     <div class="card-body pt-0">
    //                         <div class="row">
    //                             <div class="col-12">
    //                                 <h2 class="lead"><b>${tempProducts}</b></h2>
    //                                 <div class="card-obs">
    //                                     <h5>Observaciones</h5>
    //                                     <p>${pedido.observ}</p>
    //                                 </div>
    //                             </div>                           
    //                         </div>
                
    //                         <button class='terminado btn btn-sm btn-success'>
    //                             <i class='fas fa-plus-square mr-2'></i>Entregado
    //                         </button>
    //                         <button class='orden btn btn-sm btn-primary'>
    //                             <i class='fas fa-plus-square mr-2'></i>Editar Pedido
    //                         </button>
    //                         <button class='lib_mesa btn btn-sm btn-warning'>
    //                             <i class='fas fa-plus-square mr-2'></i>Liberar Mesa
    //                         </button>
    //                     </div>                        
    //                 </div>
    //             </div>
    //             `;   
    //         });
    //         $('#cb-pedidos').html(templateS);
    //     });
    // }

    /* AL HACER CLICK EN EL BOTON VER ORDEN */
/*     $(document).on('click','.verOrden',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('mesaId');
        // window.location.href ='edicionOrden.php' + "?id=" + ID; 
    }); */

    /* AL HACER CLICK EN EL BOTON EDITAR ORDEN */
    $(document).on('click','.editarOrden',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('idOrden');
        window.location.href ='edicionOrden.php' + "?idOrden=" + ID; 
    });

    /* AL HACER CLICK EN EL BOTON EDITAR ORDEN */
    $(document).on('click','.nuevaOrden',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('mesaId');
        console.log(ID);
        window.location.href ='nuevaOrden.php' + "?mesaId=" + ID; 
    });


    /* AL HACER CLICK EN EL BOTON DE TERMINADO */
    $(document).on('click','.terminado',(e)=>{
        funcion = 16;
        console.log("teminado");
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('idPedido');
        console.log(ID);

        $.post(PEDIDOS_CTRL,{funcion,ID},(response)=>{
            console.log(response);
            listarPedidos()
        })

    });

    /* AL HACER CLICK EN EL BOTON DE orden */
    $(document).on('click','.orden',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('mesaId');
        window.location.href ='edicionOrden.php' + "?id=" + ID; 
    });


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


    // listarProdCons();
    // function listarProdCons(){
    //     funcion = "listarPedidos";
    //     $.post(PEDIDOS_CTRL,{funcion},(response)=>{
    //         // console.log(response);
   
    //     })
    // }

})


/* USAR SI EL CLIENTE QUIERE UNA DATATABLE: */
// let datatable = $('#tabla_pedidos').DataTable( {

//     "scrollX": true,
//     "order": [[ 0, "asc" ]],



//     ajax: "data.json",
    
//     "ajax": {
        
//         "url":"../controllers/pedidoController.php",
//         "method":"POST",
//         "data":{funcion:funcion},
//         "dataSrc":""
//     },
//     "columns": [

//         { "data": "idPedido" },
//         { "data": "idMesa" },
//         { "data": "cant" },
//         { "data": "idProd" }
//     ],
//     language: espanol,
// } );



/* DATATABLE A ESPAÑOL */
let espanol = {
    "aria": {
        "sortAscending": "Activar para ordenar la columna de manera ascendente",
        "sortDescending": "Activar para ordenar la columna de manera descendente"
    },
    "autoFill": {
        "cancel": "Cancelar",
        "fill": "Rellene todas las celdas con <i>%d&lt;\\\/i&gt;<\/i>",
        "fillHorizontal": "Rellenar celdas horizontalmente",
        "fillVertical": "Rellenar celdas verticalmentemente"
    },
    "buttons": {
        "collection": "Colección",
        "colvis": "Visibilidad",
        "colvisRestore": "Restaurar visibilidad",
        "copy": "Copiar",
        "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
        "copySuccess": {
            "1": "Copiada 1 fila al portapapeles",
            "_": "Copiadas %d fila al portapapeles"
        },
        "copyTitle": "Copiar al portapapeles",
        "csv": "CSV",
        "excel": "Excel",
        "pageLength": {
            "-1": "Mostrar todas las filas",
            "1": "Mostrar 1 fila",
            "_": "Mostrar %d filas"
        },
        "pdf": "PDF",
        "print": "Imprimir"
    },
    "decimal": ",",
    "emptyTable": "No se encontraron resultados",
    "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
    "infoThousands": ",",
    "lengthMenu": "Mostrar _MENU_ registros",
    "loadingRecords": "Cargando...",
    "paginate": {
        "first": "Primero",
        "last": "Último",
        "next": "Siguiente",
        "previous": "Anterior"
    },
    "processing": "Procesando...",
    "search": "Buscar:",
    "searchBuilder": {
        "add": "Añadir condición",
        "button": {
            "0": "Constructor de búsqueda",
            "_": "Constructor de búsqueda (%d)"
        },
        "clearAll": "Borrar todo",
        "condition": "Condición",
        "data": "Data",
        "deleteTitle": "Eliminar regla de filtrado",
        "leftTitle": "Criterios anulados",
        "logicAnd": "Y",
        "logicOr": "O",
        "rightTitle": "Criterios de sangría",
        "title": {
            "0": "Constructor de búsqueda",
            "_": "Constructor de búsqueda (%d)"
        },
        "value": "Valor"
    },
    "searchPanes": {
        "clearMessage": "Borrar todo",
        "collapse": {
            "0": "Paneles de búsqueda",
            "_": "Paneles de búsqueda (%d)"
        },
        "count": "{total}",
        "countFiltered": "{shown} ({total}",
        "emptyPanes": "Sin paneles de búsqueda",
        "loadMessage": "Cargando paneles de búsqueda",
        "title": "Filtros Activos - %d"
    },
    "select": {
        "1": "%d fila seleccionada",
        "_": "%d filas seleccionadas",
        "cells": {
            "1": "1 celda seleccionada",
            "_": "$d celdas seleccionadas"
        },
        "columns": {
            "1": "1 columna seleccionada",
            "_": "%d columnas seleccionadas"
        }
    },
    "thousands": ",",
    "zeroRecords": "No se encontraron resultados",
    "datetime": {
        "previous": "Anterior",
        "next": "Proximo",
        "hours": "Horas",
        "minutes": "Minutos",
        "seconds": "Segundos",
        "unknown": "-",
        "amPm": [
            "am",
            "pm"
        ]
    },
    "editor": {
        "close": "Cerrar",
        "create": {
            "button": "Nuevo",
            "title": "Crear Nuevo Registro",
            "submit": "Crear"
        },
        "edit": {
            "button": "Editar",
            "title": "Editar Registro",
            "submit": "Actualizar"
        },
        "remove": {
            "button": "Eliminar",
            "title": "Eliminar Registro",
            "submit": "Eliminar",
            "confirm": {
                "_": "¿Está seguro que desea eliminar %d filas?",
                "1": "¿Está seguro que desea eliminar 1 fila?"
            }
        },
        "error": {
            "system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\\\\\/a&gt;).&lt;\\\/a&gt;<\/a>"
        },
        "multi": {
            "title": "Múltiples Valores",
            "info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, hacer click o tap aquí, de lo contrario conservarán sus valores individuales.",
            "restore": "Deshacer Cambios",
            "noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo."
        }
    }
} 