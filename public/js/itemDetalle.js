$(document).ready(function(){

    /******************************************************************************/
    /** LISTADO CODIGOS FUNCIONES
     * 150: Leer datos item
     * 151: cargar ingredientes X item

    */
    /******************************************************************************/


    var funcion     = 0;
    const ITEM_ID   = $('#itemId').val();
    const ITEM_CTRL = '../controllers/itemController.php';
    // var CODIGO_BARRA = 

    cargarDatosItem();


    function cargarDatosItem(){

        funcion = 150;
        $.post(ITEM_CTRL, {funcion, ITEM_ID},(response) => {
            // console.log(response);
            const ITEM_DATOS = JSON.parse(response);
            // console.log(ITEM_DATOS);

            ITEM_DATOS.forEach(itemDato=>{

                // console.log(itemDato.nombre);

                let IVAStr = itemDato.nombre == 1 ? 'Si' : 'No';

                // $('#tit-item').val(itemDato.nombre);
                $('#tit-item').html(itemDato.nombre);
                $('#codbar').html(itemDato.codbar);
                $('#precio').html(itemDato.precio);
                $('#iva').html(IVAStr);
                // CODIGO_BARRA = itemDato.codbar;

                listarIngredsItem(ITEM_ID)


            })
            // //tit-item
            

        })

    }


    function listarIngredsItem(idItem){

        funcion = 151;

        $.post(ITEM_CTRL,{funcion, idItem},(response)=>{

            // console.log(response);

            const INGREDS = JSON.parse(response);
            let template = '';
            INGREDS.forEach(ingred=>{
                template+=`
                <tr ingredId="${ingred.idIngred}" ingredNom="${ingred.nomIngred}">
                    <td>${ingred.nomIngred}</td>
                    <td>${ingred.cantidad} <span> ${ingred.medida}</span></td>
                </tr>
                `;
            });
            $('#tb-ingreds-item').html(template);
        })
    }


    $(document).on('click','#nIngredItem',(e)=>{

        window.location.href ='nuevoIngredItem.php' + "?id=" + ITEM_ID; 
    
    })

})