$(document).ready(function(){
    var funcion;
    var edit = false;   // bandera
    var nomProd =""; // Contiene el nombre del producto al ser creado, usada al asignar lote auto
    const URL_ITEM_CONTROL   = '../controllers/itemController.php';
    const URL_CATEG_ITEM_CONTROL = '../controllers/tipoController.php';
    const URL_PRESENT_ITEM_CONTROL = '../controllers/presentacionController.php';


    // const URL_ITEM_CONTROL   = 

    /* buscara los campos de lista desplegable con la clase select2 + la funcion interna select2*/
    $('.select2').select2();

    /* Variable que almacena la DataTable.                             */
    /* lee los datos de la BD y los distribuye en sus filas y columnas */

    funcion = 140;
    let datatable = $('#tabla_products').DataTable( {

        "scrollX": true,
        "order": [[ 2, "asc" ]],

        ajax: "data.json",
        
        "ajax": {
            
            "url":URL_ITEM_CONTROL,
            "method":"POST",
            "data":{funcion:funcion},
            "dataSrc":""
        },
        "columns": [

            { "defaultContent": `

                <button class="editar btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#editItem">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button class="borrar btn btn-sm btn-danger">
                    <i class="fas fa-trash-alt"></i>
                </button>

                <button class="ingredientes btn btn-warning">Ingredientes</button>


            `},
            { "data": "codbar" },
            { "data": "tipo" },
            { "data": "nombre" },
            { "data": "presentacion" },
            { "data": "precio" }
        ],
        language: espanol,
    } );


    // listarProdCons();
    // function listarProdCons(){
    //     funcion = "listarProducts";
    //     $.post('../controllers/productoController.php',{funcion},(response)=>{
    //         console.log(response);
   
    //     })
    // }


    /******************************************************************************/
    /* Estas funciones carga los datos a las listas desplegables del formulario   */
    /* de registrar y editar                                                      */
    /******************************************************************************/

    listar_tipos();
    function listar_tipos(){
        funcion = "listar_tipos";
        $.post(URL_CATEG_ITEM_CONTROL,{funcion},(response)=>{
            // console.log(response);
            const TIPOS = JSON.parse(response);
            let template = '';
            TIPOS.forEach(tipo=>{
                template+=`
                    <option value="${tipo.id_tipo}">${tipo.nom_tipo}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#prod_tipo').html(template);
        })
    }



    listar_presents();
    function listar_presents(){
        funcion = "listar_presents";
        $.post(URL_PRESENT_ITEM_CONTROL,{funcion},(response)=>{
            // console.log(response);
            const PRESENTS = JSON.parse(response);
            let template = '';
            PRESENTS.forEach(present=>{
                template+=`
                    <option value="${present.id_present}">${present.nom_present}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#prod_pres').html(template);
        })
    }


    /* Botones */
    $(document).on('click','#newItemMenu',(e)=>{
        location.href = '../views/newItemMenu.php';
    });


    /******************************************************************************/
    /* Funciones de Crear, generar alertas, Editar y Eliminar.    */
    /*                                                                            */
    /* datatable.ajax.reload(): Recarga automaticamente la tabla al efectuar      */
    /* el crud.                                                                   */
    /******************************************************************************/

    /******************************************************************************/

    $('#editItem').on('shown.bs.modal', function(){
        // $('#edit-product').hide('fast');
        $('#codbar').focus();
    })

    $(document).on('click','#btn-crear',(e)=>{
        // console.log("click en br crear");
        $('.agregar-carrito').attr("disabled","");
        // $('#form-crear-product').trigger('reset');

        edit = false;   // bandera
    });


    /******************************************************************************/
    $('#tabla_products tbody').on('click','.editar',function(){
        let datos = datatable.row($(this).parents()).data();

        let id= datos.id_prod;
        let codbar= datos.codbar;
        let nombre= datos.nombre;
        let iva= datos.iva;
        let precio= datos.precio;
        let prod_tipo= datos.prod_tipo;
        let prod_pres= datos.prod_pres;

        console.log(codbar);

        /* Si el valor de iva proveniente de la bd es true (1) entonces añadir o no la */
        /* propiedad checked al input de iva, de esa manera aparece o no chequeado.    */
        if(iva == 1){
            $('#iva').prop("checked",true);
        }else{
            $('#iva').prop("checked",false);
        }

        /* Los $(#...) Son los identificadores del formulario */
        $('#id_edit-prod').val(id);
        $('#nombre').val(nombre);
        $('#codbar').val(codbar);
        // $('#adici').val(adici);

        let nval = 0;   // nval: nuevo valor del iva

        if($('#iva').is(':checked')){
            $('#iva').prop("value","1");
            nval = 1;
            $('#iva').val(nval).trigger('change');
        }else{
            $('#iva').prop("value","0");
            nval = 0;
            $('#iva').val(nval).trigger('change');
        }

        $('#precio').val(precio);
        $('#prod_tipo').val(prod_tipo).trigger('change');
        $('#prod_pres').val(prod_pres).trigger('change');
        //$('#codbar').attr("type","hidden");
        //$('#labcodbar').hide();
    });


    /******************************************************************************/
    /* EDITAR SUBMIT */
    $('#form-edit-product').submit(e=>{
        /* recibir los datos del formulario al hacer click en el boton submit */
        /* val(): obtiene el valor en el imput */
        let id = $('#id_edit-prod').val();
        let codbar= $('#codbar').val();
        let nombre = $('#nombre').val();
        let prod_tipo = $('#prod_tipo').val();  // Categoria
        let prod_pres = $('#prod_pres').val();
        let precio = $('#precio').val();

        if($('#iva').is(':checked')){
            $('#iva').prop("value","1");
        }else{
            $('#iva').prop("value","0");
        }
        let iva = $('#iva').val();

        // crear 145, editar 146
       funcion = 146;

        $.post(URL_ITEM_CONTROL,{funcion,id,codbar,nombre,prod_tipo,prod_pres,precio,iva},(response)=>{

            console.log(response);

            if(response=='edit'){

                /* Vaciar campos */
                $('#nombre').val('');
                $('#compos').val('');
                $('#adici').val('');
                $('#precio').val('');
                $('#editItem').modal('hide');    //cerrar el modal prod"

                datatable.ajax.reload();
            }

            if(response=='noedit'){
                $('#noadd-product').hide('slow');
                $('#noadd-product').show(1000);
                $('#noadd-product').hide(2000);
                $('#form-crear-product').trigger('reset');
                datatable.ajax.reload();
            }
            edit = false;
        })
        e.preventDefault();
    });

    /******************************************************************************/
    /* FUNCION BORRAR */

    $('#tabla_products tbody').on('click','.borrar',function(){
        let datos = datatable.row($(this).parents()).data();
        let id= datos.id_prod;
        let nom= datos.nombre;
        var funcion = 147;
        /* Alerta */
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success m-1',
              cancelButton: 'btn btn-danger m-1'
            },
            buttonsStyling: false
        })
         
        swalWithBootstrapButtons.fire({
            title: '¿Está seguro que desea eliminar el Ítem '+nom+'?',
            text: "Esta acción ya no se podrá deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                // console.log(id);
                $.post(URL_ITEM_CONTROL,{id,funcion},(response)=>{
                    datatable;
                    if(response=='borrado'){

                        swalWithBootstrapButtons.fire(
                            'Eliminado '+nom+'!',
                            'El producto ha sido eliminado.',
                            'success'
                        )
                        datatable.ajax.reload();
                      
                    }else{
                        swalWithBootstrapButtons.fire(
                            'Error al eliminar '+nom,
                            'El producto está siendo usado en un lote o venta.',
                            'error'
                        )
                        datatable.ajax.reload();
                    }
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
    });


    /**
     * DETALLES DEL ITEM
     * Toma el id del item para cargar los datos de ese plato
     * en una nueva vista detallada con los ingredinetes
    */
    $('#tabla_products tbody').on( 'click', '.ingredientes', function () {

        /* Obtener los datos de la fila seleccionada */
        let datos = datatable.row($(this).parents()).data();
 
        let id_prod= datos.id_prod;
    
        window.location.href ='itemDetalle.php' + "?id=" + id_prod; 
    });


    /******************************************************************************/
    /* Generar un pdf con los productos del inventario                            */
    /******************************************************************************/
    $(document).on('click','#btn-reporte',(e)=>{
        funcion = 'rep_prod';
        $.post('../controllers/productoController.php',{funcion},(response)=>{
            console.log(response);
            /* Blanc es para que abra una estaña nueva */
            window.open('../pdf/pdf-'+funcion+'.pdf','_blank');
        });
    }) 

    $('#tabla_products tbody').on( 'click', '.ingreds', function () {

       /* Obtener los datos de la fila seleccionada */
        //    let datos = dtVehiculos.row(this).data();
        let datos = datatable.row($(this).parents()).data();

       let id_prod= datos.id_prod;
   
       window.location.href ='ingrediente.php' + "?id=" + id_prod; 
    });
});


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