$(document).ready(function(){
    
    var funcion
    var idCat="";
    var datatable="";
    const ITEM_ID   = $('#itemId').val();
    const URL_ITEM_CONTROL   = '../controllers/itemController.php';
    const URL_INGRED_CONTROL = '../controllers/ingredController.php';
    const URL_TIPOINGRED_CONTROL ='../controllers/invTipoController.php';

    cargarNombreItem()
    listarTipoIngred();
    mostrarProducts()
    listarIngredsItem(ITEM_ID)
    recuperarLSRecarga();


    $(".select2").select2({
        placeholder: "Seleccione una opcion",
    });


    /* Botones */
    $(document).on('click','.salir',(e)=>{
        window.location.href ='itemDetalle.php' + "?id=" + ITEM_ID; 
    });


    $('#tabla_products tbody').off('click','.agregar-carrito').on('click','.agregar-carrito',function(){
        console.log("add ing");  
        let datos = datatable.row($(this).parents()).data();

        const medida = datos.medida;
        const NOMB    = datos.nombre;
        const ID      = datos.id_prod;      // Id del Ingrediente
        let CANT      = $('#'+ID).val();
        // const PLATO   = $('#id_plato').val();
        let template  = "";

  

        // const CATEG = datos.categ;

        // console.log("id:"+ID+" nom:"+NOMB+" cant:"+CANT);

        const PRODUCTO = {
            id_prod  : ID,
            nombre   : NOMB,
            medida  : medida,
            cantidad : CANT,
            // id_plato : PLATO
        }


        /* verificar si el producto existe ya en el carr */
        let id_prod;
        let ingreds;
        ingreds = recuperarLS();
        ingreds.forEach(prod=>{
            if(prod.id_prod === PRODUCTO.id_prod){
                id_prod = prod.id_prod
            }
        })

        if(id_prod === PRODUCTO.id_prod){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ya ingresaste este producto al carrito',
            })
        }else if(PRODUCTO.cantidad == 0){
            Swal.fire({
                icon: 'error',
                title: 'Atención',
                text: 'Debe ingresar una cantidad diferente a cero',
            })
        }else{
            template=`
            <tr prodId="${PRODUCTO.id_prod}">
                <td>${PRODUCTO.nombre}</td>
                <td>${PRODUCTO.cantidad} ${PRODUCTO.medida}</td>
                <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
            </tr>
            `;
            $('#tb-nIngr-Item').append(template);
            agregarLS(PRODUCTO);
        }
    });


    function cargarNombreItem(){
        funcion = 150;

        $.post(URL_ITEM_CONTROL,{funcion, ITEM_ID},(response)=>{
            const ITEMS = JSON.parse(response);
 
            ITEMS.forEach(item=>{
                $('#nombre-item').html(item.nombre); 
            });
        })
    }


    function listarIngredsItem(idItem){
        // console.log("item: " + idItem);

        funcion = 151;

        $.post(URL_ITEM_CONTROL,{funcion, idItem},(response)=>{

            // console.log("rp: "+response);

            const INGREDS = JSON.parse(response);
            let template = '';
            INGREDS.forEach(ingred=>{
                template+=`
                <tr ingredId="${ingred.idIngred}" ingredNom = "${ingred.nomIngred}" ingredCant = "${ingred.cantidad}" nomMedida = "${ingred.medida}">
                    <td>${ingred.nomIngred}</td>
                    <td>${ingred.cantidad} <span> ${ingred.medida}</span></td>
                    <td>
                        <button class="editar-ingred btn btn-success" title="editar" type="button" data-toggle="modal" data-target="#edit-ingred">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
    
                        <button class="borrar-ingred btn btn-danger" title="borrar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
            });
            $('#tb-ingreds-item').html(template);
        })
    }


    // Al hacer clic en Guardar cambios de los nuevos ingredientes de item
    $(document).on('click','#procesarNIngredItem',(e)=>{

        let ingreds    = [];
        let json       = '';
        let idItemMenu = ITEM_ID;
  
        ingreds = recuperarLS();
        if(ingreds.length === 0){
            Swal.fire({
                icon: 'error',
                title: 'Atencion',
                text: 'No Asignaste Ingredientes Al Ítem',
            })
        }else{

            funcion = 160;

            json = JSON.stringify(ingreds);
            // console.log(json);
            $.post(URL_ITEM_CONTROL,{funcion, idItemMenu, json},(response=>{
                // console.log("RESPONDE: "+response);
                if(response != 'add'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Atencion',
                        text: response + ' Ya pertenece a los ingredientes del ítem',
                    })
                }else{
                    eliminarLS();
                    $('#tbd-lista-ing').empty();
                    $(".select2").val('').trigger('change');
                    window.location.href ='itemDetalle.php' + "?id=" + ITEM_ID;
                }
            }));

        }
    })


    /* Borrar Ingrediente de la lista de ingredintes del item */
    $(document).on('click','.borrar-producto',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('prodId');
        ELEM.remove();
        eliminarProdLS(ID);
        
        // calcularTotal()
    })

    /* VACIAR tabla */
    $(document).on('click','#vaciar-carrito-ing',(e)=>{
        /* borra todos los elementos del tbody */
        $('#tb-nIngr-Item').empty();
        eliminarLS();
        
        // calcularTotal()
    });


    $(document).on('click','.editar-ingred',()=>{

        const ELEM   = $(this)[0].activeElement.parentElement.parentElement;
        const ID     = $(ELEM).attr('ingredId');
        const NOMB   = $(ELEM).attr('ingredNom');
        const CANT   = $(ELEM).attr('ingredCant');
        const MEDIDA = $(ELEM).attr('nomMedida');

        $('#id_edit_ingred').val(ID);
        $('#nomIngred').html(NOMB);
        $('#ingred_cant').val(CANT);
        $('#nomMedida').html(MEDIDA);

    });


    /* Editar Submit */
    $('#form_edit_ingred').submit(e=>{

        let ingred_cant = $('#ingred_cant').val();
        let id_editado  = $('#id_edit_ingred').val();
        
        funcion = 161;

        $.post(URL_ITEM_CONTROL,{ingred_cant,id_editado,funcion},(response)=>{
            // console.log(response)
            if(response == 'edit'){
                $('#edit_ingred').hide('slow');
                $('#edit_ingred').show(1000);
                $('#edit_ingred').hide(2000);
                $('#form_edit_ingred').trigger('reset');
                listarIngredsItem(ITEM_ID);
            }
            else{
                $('#no_edit_ingred').hide('slow');
                $('#no_edit_ingred').show(1000);
                $('#no_edit_ingred').hide(2000);
                $('#form_edit_ingred').trigger('reset');
                listarIngredsItem(ITEM_ID);
            }
        });
        e.preventDefault();
    });


    //borrar-ingred
    $(document).on('click','.borrar-ingred',(e)=>{
        var funcion = 162;
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('ingredId');
        const NOMB = $(ELEM).attr('ingredNom');
        // console.log(ID + NOMB);

        // Alerta
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-danger mr-1'
            },
            buttonsStyling: false
          })
          
          swalWithBootstrapButtons.fire({
            title: '¿Eliminar ' + NOMB + '?',
            text: "Esta acción no se podrá deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
          }).then((result) => {

            if (result.value) {

                $.post(URL_ITEM_CONTROL,{ID,funcion},(response)=>{

                    if(response == 'borrado'){
                        swalWithBootstrapButtons.fire(
                            'Eliminado ' + NOMB,
                            'El registro ha sido eliminado correctamente',
                            'success'
                        )
                        listarIngredsItem(ITEM_ID);
                    }else{
                        swalWithBootstrapButtons.fire(
                            'Error!'+NOMB+'!',
                            'error al eliminar.',
                            'error'
                        )
                    }
                })
            }
        })
    })









    /***********************************************
     * FUNCIONAMIENTO LOCALSTORAGE
    ************************************************/ 

    function recuperarLS(){
        let ingreds;
        if(localStorage.getItem('nIngredsItem')===null){
            ingreds=[];
            console.log("ingrds: "+ingreds);
        }else{
            ingreds = JSON.parse(localStorage.getItem('nIngredsItem'));
            // console.log("ingrds: "+ingreds);
        }
        return ingreds;
    }

    /* Recupera el localstarage al recargar la pagina */
    function recuperarLSRecarga(){
        let ingreds;
        if(localStorage.getItem('nIngredsItem')===null){
            ingreds=[];
        }else{
            let template="";
            ingreds = JSON.parse(localStorage.getItem('nIngredsItem'));

            ingreds.forEach(prod => {
                // console.log("ls: "+ prod.nombre);
                template=`

                <tr prodId="${prod.id_prod}">
                    <td>${prod.nombre}</td>
                    <td>${prod.cantidad} ${prod.medida}</td>
                    <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
                </tr>

                `;
                $('#tb-nIngr-Item').append(template);
                
            });

        }
        return ingreds;
    }

    /* AGREGAR Producto al LOCAL STORAGE */
    function agregarLS(ingred){
        let ingreds;
        ingreds = recuperarLS();
        ingreds.push(ingred);
        localStorage.setItem('nIngredsItem',JSON.stringify(ingreds));
    }

    function eliminarProdLS(ID){
        let ingreds;
        ingreds = recuperarLS();
        ingreds.forEach(function(ingred,indice){
            if(ingred.id_prod === ID){
                ingreds.splice(indice,1);
            }
        });
        localStorage.setItem('nIngredsItem',JSON.stringify(ingreds));
    }

    function eliminarLS(){
        localStorage.clear();
    }







    /***********************************************
     * FUNCIONAMIENTO DATATABLE INGREDIENTES
    ************************************************/ 
    
     function listarTipoIngred(){
        funcion = "listarTipoIngred";
        $.post(URL_TIPOINGRED_CONTROL,{funcion},(response)=>{
            // console.log(response);
            const TIPOS = JSON.parse(response);
            let template = '';
            TIPOS.forEach(tipo=>{
                template+=`
                    <option value=""></option>
                    <option value="${tipo.id_tipo}">${tipo.nom_tipo}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#tipo_ing').html(template);
        })
    }


    /* Carga los productos en la tabla segun la categoria indicada */
    function mostrarProducts(){
        // console.log('listarIngredsCateg');
        funcion = "listarIngredsCateg";
        datatable = $('#tabla_products').DataTable({

            "scrollX": true,
            "order": [[ 2, "asc" ]],
    
            ajax: "data.json",
            
            "ajax": {
                
                "url":URL_INGRED_CONTROL,
                "method":"POST",
                "data":{funcion:funcion, idCat:idCat},
                "dataSrc":""
            },
            "columns": [

            
                { "data": "cant" },
                { "data": "medida" },
                { "data": "nombre" },
                { "data": "id_prod" },
            
            ],
            language: espanol,
        });

        datatable.columns.adjust().draw();
        
    }


    $( "#tipo_ing" ).change(function() {  
        datatable.destroy();
        idCat = $('#tipo_ing').val();
        mostrarProducts()
    });


    /* Evento que controla el input de cantidad y sus flechas */
    $(document).on('click', '.btn-plus, .btn-minus', function(e) {
        const isNegative = $(e.target).closest('.btn-minus').is('.btn-minus');
        const input = $(e.target).closest('.input-group').find('input');
        if (input.is('input')) {
        input[0][isNegative ? 'stepDown' : 'stepUp']()
        }
    })


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