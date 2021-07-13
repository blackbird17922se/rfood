$(document).ready(function(){

    consultarTipos();

    var funcion;
    var edit = false;   // bandera

    /* Formukario crear editar */
    $('#form-crear-tipo').submit(e=>{
        let nom_tipo = $('#nom_tipo').val();
        let id_editado = $('#id_edit_tipo').val();

        if(edit==false){
            funcion="crear";
        }else{
            funcion="editar";
        }

        $.post('../controllers/invTipoController.php',{nom_tipo,id_editado,funcion},(response)=>{
            console.log(response)
            if(response=='add'){
                $('#add-tipo').hide('slow');
                $('#add-tipo').show(1000);
                $('#add-tipo').hide(2000);
                $('#form-crear-tipo').trigger('reset');
                consultarTipos();
            }
            if(response=='noadd'){
                $('#noadd-tipo').hide('slow');
                $('#noadd-tipo').show(1000);
                $('#noadd-tipo').hide(2000);
                $('#form-crear-tipo').trigger('reset');
            }
            if(response=='edit'){
                $('#edit-tipo').hide('slow');
                $('#edit-tipo').show(1000);
                $('#edit-tipo').hide(2000);
                $('#form-crear-tipo').trigger('reset');
                consultarTipos();
            }
            edit=false;
        });
        e.preventDefault();
    });


    function consultarTipos(consulta){
        funcion = 'consultarTipos';
        // ajax
        $.post('../controllers/invTipoController.php',{consulta,funcion},(response)=>{
            // console.log(response);
            const TIPOS = JSON.parse(response);
            let template = '';
            TIPOS.forEach(tipo=>{
                template+=`
                <tr tipoId="${tipo.id_inv_tipo}" tipoNom="${tipo.nom}">
                    <td>${tipo.nom}</td>
                    <td>
                        <button class="editar-tipo btn btn-success" title="editar" type="button" data-toggle="modal" data-target="#crear-tipo">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
    
                        <button class="borrar-tipo btn btn-danger" title="borrar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
            });
            $('#tbd-tipos').html(template);
        })
    }


    // evento para las busquedas
    $(document).on('keyup','#busq-tipo',function(){
        let valor = $(this).val();
        if(valor != ''){
            consultarTipos(valor);
        }else{
            consultarTipos();
        }
    });


    $(document).on('click','.borrar-tipo',(e)=>{
        var funcion = "borrar";
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('tipoId');
        const NOMB = $(ELEM).attr('tipoNom');
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
                $.post('../controllers/invTipoController.php',{ID,funcion},(response)=>{
                    // console.log(response);
                    edit==false;
                    if(response=='borrado'){
                        swalWithBootstrapButtons.fire(
                            'Eliminada ' + NOMB,
                            'tipooría eliminada correctamente',
                            'success'
                        )
                        consultarTipos();
                    }else{
                        swalWithBootstrapButtons.fire(
                            '¡No se pudo eliminar '+NOMB+'!',
                            'La tipooría está siendo usada en algún producto.',
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
    

    $(document).on('click','.editar-tipo',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('tipoId');
        const NOMB = $(ELEM).attr('tipoNom');
        $('#id_edit_tipo').val(ID);
        $('#nom_tipo').val(NOMB);
        edit=true;
    })
});