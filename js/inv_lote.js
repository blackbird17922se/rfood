$(document).ready(function(){

    var funcion;
    var edit = false;   // bandera
    const URL_INV_LOTE = '../controllers/invLoteController.php';

    cargarLotes();


    /* nuevo varga lotes */
    function cargarLotes(){
        funcion = 2;
        datatable = $('#tb_lotes').DataTable({

            "scrollX": true,
            "order": [[ 1, "asc" ]],
    
            ajax: "data.json",
            
            "ajax": {                
                "url":URL_INV_LOTE,
                "method":"POST",
                "data":{funcion:funcion},
                "dataSrc":""
            },



            "columns": [

                { "data": "id_lote" },
                { "data": "nombre" },
                { "data": "stock" },
                { "data": "vencim" },
                { "data": "prov_nom" },
                { "defaultContent": `
                    <button class="ver btn btn-transp" type="button"><img src="../public/icons/document-save-as.png" alt=""></button>
                    <button class=" btn btn-transp"><img src="../public/icons/delete_32.png" alt=""></button>
                    
                `}, 
            ],
            language: espanol,
        });

        datatable.columns.adjust().draw();
       
    }







    /* **************************************************************************************************** */

    function cargarLotesold(consulta){
        funcion = 'cargarLotes';

        $.post('../controllers/invLoteController.php',{consulta,funcion},(response)=>{
            console.log("carga: " + response);

            const LOTES = JSON.parse(response);
            let template = '';

            LOTES.forEach(lote=>{
                template+=`
                <div loteId="${lote.id_lote}" loteStock="${lote.stock}" class="col-12 col-sm-6 col-md-3 align-items-stretch">`;
                if(lote.estado=='success'){
                    template+=`<div class="card bg-success">`;
                }
                if(lote.estado=='warning'){
                    template+=`<div class="card bg-warning">`;
                }
                if(lote.estado=='danger'){
                    template+=`<div class="card bg-danger">`;
                }
                
                template+=`<div class="card-header border-bottom-0">
                <i class="fas fa-lg fa-cubes mr-1"></i>${lote.stock} ${lote.medida_nom} en total
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-12">
                      <h2 class="lead"><b>${lote.nombre}</b></h2>
                      <h2 >Codigo lote: <b>${lote.id_lote}</b></h2>

                      <ul class="ml-4 mb-0 fa-ul">
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-copyright"></i></span>Tipo: ${lote.tipo}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-truck"></i></span>Proveedor: ${lote.prov_nom}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-calendar-times"></i></span>Vencimiento: ${lote.vencim}</li>

                        
                      </ul>
                    </div>
                   
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <button class="editar btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#editarlote">
                        <i class="fas fa-pencil-alt"></i>
                    </button>

                    <button class="borrar btn btn-sm btn-danger">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
             
                `;
            });
            $('#cb-lotes').html(template);
        })
    }

    /* BUSCAR */
    $(document).on('keyup','#buscar-lote',function(){
        let valor = $(this).val();
        if(valor != ''){
            cargarLotes(valor);
        }else{
            cargarLotes();
        }
    });


    $(document).on('click','.editar',(e)=>{
        
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('loteId');
        const STOCK = $(ELEM).attr('loteStock');

        /* LOS ID# SON LOS DE LOS CAMPOSDEL FORM */
        $('#lote_id_prod').val(ID);
        $('#stock').val(STOCK);
        $('#id_lote').html(ID);
        edit = true;   // bandera
    })


    $(document).on('click','.lote',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('prodId');
        const NOMB = $(ELEM).attr('prodnombre');

        $('#lote_id_prod').val(ID);
        $('#nom_lote_lote').html(NOMB);
        // console.log(ID + '-' + NOMB);
        edit = true;   // bandera
    })


    /* FUN BORRAR */
    $(document).on('click','.borrar',(e)=>{
        var funcion = "borrar";
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('loteId');
        // const NOMB = $(ELEM).attr('loteStock');
        console.log(ID);

        // Alerta

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-danger mr-1'
            },
            buttonsStyling: false
          })
          
          swalWithBootstrapButtons.fire({
            title: '¿Está seguro que desea eliminar el lote '+ID+'?',
            text: "Esta acción ya no se podrá deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
          }).then((result) => {
            if (result.value) {
                $.post('../controllers/invLoteController.php',{ID,funcion},(response)=>{
                    // console.log(response);
                    edit==false;
                    if(response=='borrado'){
                        swalWithBootstrapButtons.fire(
                            'Eliminado '+ID+'!',
                            'El lote ha sido eliminado.',
                            'success'
                        )
                        cargarLotes();
                    }else{
                        swalWithBootstrapButtons.fire(
                            'Error al eliminar '+ID,
                            'El lote está siendo usado en un lote.',
                            'error'
                        )
                    }
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            //   swalWithBootstrapButtons.fire(
            //     'Cancelado',
            //     '',
            //     'error'
            //   )
            }
          })
    });

    $('#form-editar-lote').submit(e=>{

        let lote_id_prod = $('#lote_id_prod').val()
        let stock = $('#stock').val();

        funcion="editar";

        // funcion = "crear";
        $.post('../controllers/invLoteController.php',{funcion,lote_id_prod,stock},(response)=>{
            // console.log("mau " + response);
            console.log(response);


            if(response=='edit'){
                $('#edit-lote').hide('slow');
                $('#edit-lote').show(1000);
                $('#edit-lote').hide(2000);
                $('#form-crear-lote').trigger('reset');
                cargarLotes();
            }
            
            edit = false;
        })
        e.preventDefault();
    });

})

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