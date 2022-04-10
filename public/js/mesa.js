$(document).ready(function(){

    buscarMesa();

    var funcion = 0;
    var edit = false;   // bandera
    const MESA_CTRLR = '../controllers/mesaController.php';


    /* Formukario crear editar */
    $('#form-crear-mesa').submit(e=>{
        let nom_mesa = $('#nom_mesa').val();
        let id_editado = $('#id_edit_mesa').val();

        if(edit==false){
            funcion = 1;
        }else{
            funcion = 3;
        }

        // funcion = 'crear';
        $.post(MESA_CTRLR,{nom_mesa,id_editado,funcion},(response)=>{
            console.log(response)
            if(response=='add'){
                $('#add-mesa').hide('slow');
                $('#add-mesa').show(1000);
                $('#add-mesa').hide(2000);
                $('#form-crear-mesa').trigger('reset');
                buscarMesa();
            }
            if(response=='noadd'){
                $('#noadd-mesa').hide('slow');
                $('#noadd-mesa').show(1000);
                $('#noadd-mesa').hide(2000);
                $('#form-crear-mesa').trigger('reset');
            }
            if(response=='edit'){
                $('#edit-mesa').hide('slow');
                $('#edit-mesa').show(1000);
                $('#edit-mesa').hide(2000);
                $('#form-crear-mesa').trigger('reset');
                buscarMesa();
            }
            edit=false;
        });
        e.preventDefault();
    });

    function buscarMesa(consulta){
        funcion = 2;
        // ajax
        $.post(MESA_CTRLR,{consulta,funcion},(response)=>{
            // console.log(response);
            const mesaS = JSON.parse(response);
            let template = '';
            mesaS.forEach(mesa=>{
                template+=`
                <tr mesaId="${mesa.id_mesa}" mesaNom="${mesa.nom}">
                    <td>${mesa.nom}</td>
                    <td>
                        <button class="editar-mesa btn btn-success" title="editar" type="button" data-toggle="modal" data-target="#crearmesa">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
    
                        <button class="borrar-mesa btn btn-danger" title="borrar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
            });
            $('#tbd-mesas').html(template);
        })
    }
    // evento paara las busquedad
    $(document).on('keyup','#busq-mesa',function(){
        let valor = $(this).val();
        if(valor != ''){
            buscarMesa(valor);
        }else{
            buscarMesa();
        }
    });

    $(document).on('click','.borrar-mesa',(e)=>{
        var funcion = 4;
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('mesaId');
        const NOMB = $(ELEM).attr('mesaNom');
        console.log(ID + NOMB);

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
                $.post(MESA_CTRLR,{ID,funcion},(response)=>{
                    // console.log(response);
                    edit==false;
                    if(response=='borrado'){
                        swalWithBootstrapButtons.fire(
                            'Eliminado ' + NOMB,
                            'El registro ha sido eliminado correctamente',
                            'success'
                        )
                        buscarMesa();
                    }else{
                        swalWithBootstrapButtons.fire(
                            'Error!'+NOMB+'!',
                            'error al eliminar.',
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
    })

    $(document).on('click','.editar-mesa',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('mesaId');
        const NOMB = $(ELEM).attr('mesaNom');
        $('#id_edit_mesa').val(ID);
        $('#nom_mesa').val(NOMB);
        edit=true;
    })
});