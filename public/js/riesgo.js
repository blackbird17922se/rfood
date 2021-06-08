// /* MOSTRAR LOTES EN RIESGO */

$(document).ready(function(){

    var funcion = 'buscar';
    $.post('../controllers/loteController.php',{funcion},(response)=>{

        const LOTES = JSON.parse(response);
        let template = '';
        LOTES.forEach(lote=>{
    
            if(lote.estado=='danger'){
                template+=`
                    <tr class="table-danger">
                    
                        <td>${lote.id_lote}</td>
                        <td>${lote.nombre}</td>
                        <td>${lote.laboratorio}</td>
                        <td>${lote.compos}</td>
                        <td>${lote.stock}</td>
                        <td>${lote.prov_nom}</td>
                        <td>${lote.mes}</td>
                        <td>${lote.dia}</td>

                    </tr>
                `;
            }
        });
        $('#tbd-lotes').html(template);
    })
});