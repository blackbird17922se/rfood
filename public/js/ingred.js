/**
 * ingred.js
 * 
 * Continene las funciones para el modulo Ingredientes el cual 
 * permite añadir ingredintes para la creacion de los items
 * (o platos) del menu de la carta.
 */

$(document).ready(function(){
    var funcion;
    var edit = false;   // bandera
    var nomProd =""; // Contiene el nombre del producto al ser creado, usada al asignar lote auto

    const URL_INGRED_CONTROL = '../controllers/ingredController.php';

    /* buscara los campos de lista desplegable con la clase select2 + la funcion interna select2*/
    $('.select2').select2();

    /* Variable que almacena la DataTable.                             */
    /* lee los datos de la BD y los distribuye en sus filas y columnas */

    funcion = "listarProducts";
    let datatable = $('#tb_ingreds').DataTable( {

        "scrollX": true,


        ajax: "data.json",
        
        "ajax": {
            
            "url": URL_INGRED_CONTROL,
            "method":"POST",
            "data":{funcion:funcion},
            "dataSrc":""
        },
        "columns": [

            { "defaultContent": `

                <button class="editar btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#crearproduct">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button class="lote btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#crearlote">
                    <i class="fas fa-plus-square"></i>
                </button>

                <button class="borrar btn btn-sm btn-danger">
                    <i class="fas fa-trash-alt"></i>
                </button>

            `},
            { "data": "nombre" },
            { "data": "stock" },
            { "data": "medida" },
            { "data": "precio" },
            { "data": "tipo" },
            { "data": "codbar" },
        ],
        language: espanol,
    } );


    listarProdCons();
        function listarProdCons(){
        funcion = "listarProducts";
        $.post('../controllers/invProductoController.php',{funcion},(response)=>{
            console.log(response);
   
        })
    }


    /******************************************************************************/
    /* Estas funciones carga los datos a las listas desplegables del formulario   */
    /* de registrar y editar                                                      */
    /******************************************************************************/

    listarInvTipos();
    function listarInvTipos(){
        funcion = "listarInvTipos";
        $.post('../controllers/invTipoController.php',{funcion},(response)=>{
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

    listarMedidas();
    function listarMedidas(){
        funcion = "listarMedidas";
        $.post('../controllers/invMedidaController.php',{funcion},(response)=>{
            // console.log(response);
            const medidaS = JSON.parse(response);
            let template = '';
            medidaS.forEach(medida=>{
                template+=`
                    <option value="${medida.id_medida}">${medida.nom_medida}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#medida').html(template);
        })
    }

    listar_proveeds();
    function listar_proveeds(){
        funcion = "listar_proveeds";
        $.post('../controllers/proveedController.php',{funcion},(response)=>{
            // console.log(response);
            const PROVEEDS = JSON.parse(response);
            let template = '';
            PROVEEDS.forEach(proveed=>{
                template+=`
                    <option value="${proveed.id_proveed}">${proveed.nom_proveed}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#lote_id_prov').html(template);
        })
    }


    /******************************************************************************/
    /* Funciones de Crear, generar alertas, Editar y Eliminar.    */
    /*                                                                            */
    /* datatable.ajax.reload(): Recarga automaticamente la tabla al efectuar      */
    /* el crud.                                                                   */
    /******************************************************************************/

    /******************************************************************************/

    $('#crearproduct').on('shown.bs.modal', function(){
        $('#codbar').focus();
    })

    $(document).on('click','#btn-crear',(e)=>{
        // console.log("click en br crear");
        $('#codbar').attr("type","number");
        $('#labcodbar').show();
        // $('#form-crear-product').trigger('reset');

        edit = false;   // bandera
    });

    /******************************************************************************/
    /* CREAR Y ALERTAS */
    $('#form-crear-product').submit(e=>{
        /* recibir los datos del formulario al hacer click en el boton submit */
        /* val(): obtiene el valor en el imput */
        let id      = $('#idEditProd').val()
        let codbar  = $('#codbar').val();
        let nombre  = $('#nombre').val();

        if($('#iva').is(':checked')){
            $('#iva').prop("value","1");
        }else{
            $('#iva').prop("value","0");
        }

        let iva       = $('#iva').val();
        let precio    = $('#precio').val(); 
        let prod_tipo = $('#prod_tipo').val();
        let un_medida = $('#medida').val();

        if(edit==true){
            funcion="editar";
        }else{
            funcion="crear";
        }

        // funcion = edit == true ? "editar" : "crear";
        console.log(funcion+"-id:"+id+"-cb:"+codbar+"-nom:"+nombre+"-"+iva+"-"+precio+"-"+prod_tipo+"-"+un_medida);
        $.post('../controllers/invProductoController.php',{funcion,id,codbar,nombre,iva,precio,prod_tipo,un_medida},(response)=>{

            console.log(response);
            if(response=='add'){
                nomProd = nombre;
                $('#add-product').hide('slow');
                $('#add-product').show(1000);
                $('#add-product').hide(2000);

                /* Vaciar campos */
                $('#codbar').val('');
                $('#nombre').val('');
                $('#compos').val('');
                $('#adici').val('');
                $('#precio').val('');

                $('#crearproduct').modal('hide');    //Desplegar el modal de "lote"

                datatable.ajax.reload();

                $('#crearlote').modal();    //Desplegar el modal de "lote"
                // $('#form-crear-product').trigger('reset');

                asignarLoteAutomatic(); // Ejecutar asignacion de lote al prod. creado
  
            }
            if(response=='edit'){
                $('#edit-product').hide('slow');
                $('#edit-product').show(1000);
                $('#edit-product').hide(2000);

                /* Vaciar campos */
                $('#codbar').val('');
                $('#nombre').val('');
                $('#compos').val('');
                $('#adici').val('');
                $('#precio').val('');

                // $('#form-crear-product').trigger('reset');
                datatable.ajax.reload();
            }
            if(response=='noadd'){
                $('#noadd-product').hide('slow');
                $('#noadd-product').show(1000);
                $('#noadd-product').hide(2000);
                $('#form-crear-product').trigger('reset');
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

    $('#tb_ingreds tbody').on('click','.editar',function(){
        let datos = datatable.row($(this).parents()).data();

        let id          = datos.id_inv_prod;
        let codbar      = datos.codbar;
        let nombre      = datos.nombre;
        let iva         = datos.iva;
        let precio      = datos.precio;
        let prod_tipo   = datos.prod_tipo; // Lacteos...
        let un_medida   = datos.un_medida; // gr, lt...

        let nval = 0;   // nval: nuevo valor del iva

        /* Si el valor de iva proveniente de la bd es true (1) entonces añadir o no la */
        /* propiedad checked al input de iva, de esa manera aparece o no chequeado.    */
        if(iva == 1){
            $('#iva').prop("checked",true);
        }else{
            $('#iva').prop("checked",false);
        }

        /* Los $(#...) Son los identificadores del formulario */
        $('#idEditProd').val(id);
        $('#codbar').val(codbar);
        $('#nombre').val(nombre);

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
        $('#medida').val(un_medida).trigger('change');
        // $('#codbar').attr("type","hidden");

        edit = true;   // bandera
    });

    /******************************************************************************/
    /* FUNCION BORRAR */

    $('#tb_ingreds tbody').on('click','.borrar',function(){
        let datos = datatable.row($(this).parents()).data();
        let id= datos.id_inv_prod;
        let nom= datos.nombre;
        var funcion = "borrar";
        /* Alerta */
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success m-1',
              cancelButton: 'btn btn-danger m-1'
            },
            buttonsStyling: false
        })
         
        swalWithBootstrapButtons.fire({
            title: '¿Está seguro que desea eliminar el producto '+nom+'?',
            text: "Esta acción ya no se podrá deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                console.log(id);
                $.post('../controllers/invProductoController.php',{id,funcion},(response)=>{
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


    /******************************************************************************/
    /* Estas funciones proveen la asignacion de lotes a los productos             */
    /******************************************************************************/

    /*************Asignar automaticamente lotes al crear el producto***************/

    function asignarLoteAutomatic() {
        console.log("asignarLoteAutomatic");

        var ultRegistro = 0;    //Ultimo Registro

        funcion = "ultimoReg";
        $.post('../controllers/invProductoController.php',{funcion},(response)=>{

            const ULT_REG = JSON.parse(response);

            ULT_REG.forEach(registro =>{
                ultRegistro = registro.ultimo_prod;            

            });

            $('#lote_id_prod').val(ultRegistro);
            $('#nom_product_lote').html(nomProd);
    
            edit = true;   // bandera

        });        
    }


    /**Trae los datos id y nombre producto de la fila al hacer clic en asignar lote**/

    $('#tb_ingreds tbody').on('click','.lote',function(){
        console.log("asignar lote");

        let datos = datatable.row($(this).parents()).data();

        let id= datos.id_inv_prod;
        let nombre= datos.nombre;

        $('#lote_id_prod').val(id);
        $('#nom_product_lote').html(nombre);
        edit = true;   // bandera
    });

    /******************************************************************************/

    $('#form-crear-lote').submit(e=>{
        console.log("subm cr lote");

        let lote_id_prod = $('#lote_id_prod').val();
        console.log("lote_id_prod:" + lote_id_prod);
        let lote_id_prov = $('#lote_id_prov').val();
        let stock = $('#stock').val();
        let vencim = $('#vencim').val();
        funcion="crear";

        $.post('../controllers/invLoteController.php',{funcion,lote_id_prod,lote_id_prov,stock,vencim},(response)=>{
            // console.log("id prod: " + lote_id_prod+"proved: " + lote_id_prov + "stock: " +stock+ "vencim: " +vencim);
            console.log("crear lote responde:  " + response);

            if(response=='add'){
                $('#add-lote').hide('slow');
                $('#add-lote').show(1000);
                $('#add-lote').hide(2000);

                $('#stock').val('');
                $('#vencim').val('');

                // $('#form-crear-lote').trigger('reset');
                datatable.ajax.reload();
            }
            
            edit = true;
            // edit = false;
        })
        e.preventDefault();
    });


    /******************************************************************************/
    /* Generar un pdf con los productos del inventario                            */
    /******************************************************************************/
    $(document).on('click','#btn-reporte',(e)=>{
        funcion = 'rep_prod';
        $.post('../controllers/invProductoController.php',{funcion},(response)=>{
            console.log(response);
            /* Blanc es para que abra una estaña nueva */
            window.open('../pdf/pdf-'+funcion+'.pdf','_blank');
        });
    })  
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