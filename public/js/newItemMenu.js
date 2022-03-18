/* 

*/

$(document).ready(function(){

    var funcion, idCat="";

    const URL_INGRED_CONTROL = '../controllers/ingredController.php';
    const URL_ITEM_CONTROL   = '../controllers/itemController.php';
    const URL_CATEG_ITEM_CONTROL = '../controllers/tipoController.php';
    const URL_PRESENT_ITEM_CONTROL = '../controllers/presentacionController.php';
    
    $(".select2").select2({
        placeholder: "Seleccione una opcion",
    });

    $('#ing-carrito').show();


    /***********************************************
     * Lista el tipo y la presentacion del producto (o plato)
    ************************************************/ 
    listar_tipos();
    function listar_tipos(){
        funcion = "listar_tipos";
        $.post(URL_CATEG_ITEM_CONTROL,{funcion},(response)=>{
            // console.log("tipos" + response);
            const TIPOS = JSON.parse(response);
            let template = '';
            TIPOS.forEach(tipo=>{
                template+=`
                    <option value="${tipo.id_tipo}">${tipo.nom_tipo}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#cat_item').html(template);
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
            $('#pres_item').html(template);
        })
    }


    /***********************************************
     * Lista los tipos de ingredientes  que puede llevar el producto a
     * crear (Panes, Carnes, Lacteos...)
    ************************************************/ 
    listarTipoIngred();
    function listarTipoIngred(){
        funcion = "listarTipoIngred";
        $.post('../controllers/invTipoController.php',{funcion},(response)=>{
            console.log(response);
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



    var datatable="";

    mostrarProducts()
    recuperarLSRecarga()


    /* OJO---FUNCIONES DEL LOCAL STOG */

    function recuperarLS(){
        let ingreds;
        if(localStorage.getItem('ingreds')===null){
            ingreds=[];
            console.log("ingrds: "+ingreds);
        }else{
            ingreds = JSON.parse(localStorage.getItem('ingreds'));
            console.log("ingrds: "+ingreds);
        }
        return ingreds;
    }

    /* Recupera el localstarage al recargar la pagina */
    function recuperarLSRecarga(){
        let ingreds;
        if(localStorage.getItem('ingreds')===null){
            ingreds=[];
        }else{
            let template="";
            ingreds = JSON.parse(localStorage.getItem('ingreds'));
            ingreds.forEach(prod => {
                template=`
                <tr prodId="${prod.id_prod}">
                    <td>${prod.id_prod}</td>
                    <td>${prod.nombre}</td>
                    <td>${prod.medida}</td>
                    <td>${prod.cantidad}</td>
                    <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
                </tr>
                `;
                $('#tbd-lista-ing').append(template);
                
            });

        }
        return ingreds;
    }

    /* AGREGAR Producto al LOCAL STORAGE */
    function agregarLS(ingred){
        let ingreds;
        ingreds = recuperarLS();
        ingreds.push(ingred);
        localStorage.setItem('ingreds',JSON.stringify(ingreds));
    }

    function eliminarProdLS(ID){
        let ingreds;
        ingreds = recuperarLS();
        ingreds.forEach(function(ingred,indice){
            if(ingred.id_prod === ID){
                ingreds.splice(indice,1);
            }
        });
        localStorage.setItem('ingreds',JSON.stringify(ingreds));
    }

    function eliminarLS(){
        localStorage.clear();
    }


    /* Carga los productos en la tabla segun la categoria indicada */
    function mostrarProducts(){
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

    function procesarPedido(){
        let productos;
        productos = recuperarLS();
        if(productos.length === 0){
            Swal.fire({
                icon: 'error',
                title: 'Atencion',
                text: 'El Carrito esta vacio',
            })
        }else{
            funcion = 'guardarIngreds';

        /* nviar ese producto al controlador */
            let json = JSON.stringify(productos);
            console.log(json);
            $.post('../controllers/ingredienteController.php',{funcion,json},(response=>{
                console.log(response);
            }));

        }
    }

    
    // listarProdCons();
    // function listarProdCons(){
    //     // console.log("listarProdCons");
    //     console.log("idCat: " + idCat);

    //     funcion = "listarProlistarIngredsCategducts";
    //     $.post(URL_INGRED_CONTROL,{funcion, idCat},(response)=>{
    //         console.log("prod: " + response);
   
    //     })
    // }


    $( "#tipo_ing" ).change(function() {  
        datatable.destroy();
        idCat = $('#tipo_ing').val();
        // console.log(idCat);
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
        $('#tbd-lista-ing').empty();
        eliminarLS();
        
        // calcularTotal()
    });


    /**
     * el contenido de la tabla "tabla_products" y el boton agregar-carrito
     * vienen desde ingredController.php/listarIngredsCateg
    */

    $('#tabla_products tbody').off('click','.agregar-carrito').on('click','.agregar-carrito',function(){
        let datos = datatable.row($(this).parents()).data();

        const medida = datos.medida;
        const NOMB    = datos.nombre;
        const ID      = datos.id_prod;
        let CANT      = $('#'+ID).val();
        const PLATO   = $('#id_plato').val();

  

        // const CATEG = datos.categ;

        console.log("id:"+ID+" nom:"+NOMB+" cant:"+CANT);

        const PRODUCTO = {
            id_prod  : ID,
            nombre   : NOMB,
            medida  : medida,
            cantidad : CANT,
            id_plato : PLATO
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
                <td>${PRODUCTO.id_prod}</td>
                <td>${PRODUCTO.nombre}</td>
                <td>${PRODUCTO.medida}</td>
                <td>${PRODUCTO.cantidad}</td>
                <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
            </tr>
            `;
            $('#tbd-lista-ing').append(template);
            agregarLS(PRODUCTO);
            
        }   
    });


    /* Clic al Guardar Item */
    $(document).on('click','#procesarItemMenu',(e)=>{

        let ingreds   = [];
        let json      = '';

        let codbar    = 0;
        let cat_item  = '';
        let nombre    = '';
        let pres_item = '';
        let precio    = 0;
        let iva       = 0;

        ingreds = recuperarLS();
        
        // Verificar check del IVA
        if($('#iva').is(':checked')){
            $('#iva').prop("value","1");
        }else{
            $('#iva').prop("value","0");
        }

        funcion   = 145;

        codbar    = $('#codbar').val();
        cat_item  = $('#cat_item').val();
        nombre    = $('#nombre').val();
        pres_item = $('#pres_item').val();
        precio    = $('#precio').val();
        iva       = $('#iva').val();


    /* nviar ese producto al controlador */
        json = JSON.stringify(ingreds);
        console.log(json);
        $.post(URL_ITEM_CONTROL,{funcion,codbar,cat_item,nombre,pres_item,precio,iva,json},(response=>{
            console.log("RESP MEN: "+response);
            if(response == 'addItem'){
                Swal.fire({
                    title: 'Agregado ' + nombre + ' Exitosamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    reverseButtons: true
                });

                eliminarLS();
                $('#codbar').val('');
                $('#nombre').val('');
                $('#adici').val('');
                $('#precio').val('');
                $('#tbd-lista-ing').empty();
                $(".select2").val('').trigger('change');

            }else if(response == 'noAddItem'){
                Swal.fire({
                    title: 'Error al registrar ' + nombre,
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                    reverseButtons: true
                });
            }
        }));
    })


/*     $(document).on('click','#procesarItemMenu',(e)=>{
        console.log("procesarItemMenu");
        procesarItemMenu();
        eliminarLS();
        $('#tbd-lista-ing').empty();
        $(".select2").val('').trigger('change');
    }) */




    /* **************************************************** */
    /*  FUNCIONES PARA AGREGAR INGREDS A UN ITEM YA CREADO  */
    /* **************************************************** */

    $(document).on('click','#procesarNIngredItem',(e)=>{
        console.log("procesarNIngredItem");
        procesarNIngredItem();
        eliminarLS();
        $('#tbd-lista-ing').empty();
        $(".select2").val('').trigger('change');
    })


    
    function procesarNIngredItem(){

        let ingreds   = [];
        let json      = '';

        ingreds = recuperarLS();
        if(ingreds.length === 0){
            Swal.fire({
                icon: 'error',
                title: 'Atencion',
                text: 'No Asignaste Ingredientes Al Ítem',
            })
        }else{

            funcion   = 160;

        /* nviar ese producto al controlador */
            json = JSON.stringify(ingreds);
            console.log(json);
            $.post(URL_ITEM_CONTROL,{funcion,codbar,cat_item,nombre,pres_item,precio,iva,json},(response=>{
                console.log("RESP MEN: "+response);

                if (response=="errorAddItem") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Fatal',
                        text: 'Ha ocurrido un error interno al agregar el ítem.',
                    })   
                }

            }));
        }
    }

    $(document).on('click','.salir',(e)=>{
        location.href = '../views/adm_menu.php';
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