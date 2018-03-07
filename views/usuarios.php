<?php include PATH_ROOT . '/template/head.php'; ?>

<a id="btnCreate" href="#" class="btn-floating btn-large waves-effect waves-light blue">
    <i class="fa fa-plus"></i>Agregar
</a>

<input id="id" type="hidden" value=""/>
<!-- Modal -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetail">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modalDetail">Usuarios</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 form-group" style="margin-bottom:5px">
                <label class="control-label"  for="cedula">Cédula</label>
                <input id="cedula" type="text" class="form-control">
            </div>

            <div class="col-md-6 form-group" style="margin-bottom:5px">
                <label for="nombres">Nombres</label>
                <input id="nombres" type="text" class="form-control">
            </div>

            <div class="col-md-6 form-group" style="margin-bottom:5px">
                <label for="apellidos">Apellidos</label>
                <input id="apellidos" type="text" class="form-control">
            </div>

            <div class="col-md-12 form-group" style="margin-bottom:5px">
                <label class="control-label" for="email">Email</label>
                <input id="email" type="email" class="form-control">
            </div>

            <div class="col-md-8 form-group" style="margin-bottom:5px">
                <label for="nacimiento">Fecha de nac.</label>
                <input id="nacimiento" type="date" class="form-control">
            </div>

            <div class="col-md-4 form-group" style="margin-bottom:5px">
                <label for="edad">Edad</label>
                <input id="edad" type="number" min="1" max="100" class="form-control">
            </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button id="btnStore" type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>


<body>

    <div class="container">
    <h1><?=$title?></h1>
        
        <table id="example" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Email</th>                    
                    <th>Edad</th>
                    <th>Fecha de Nac.</th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Cédula</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Email</th>                    
                    <th>Edad</th>
                    <th>Fecha de Nac.</th>
                    <th></th>
                </tr>
            </tfoot>
            <tbody>

                <?php foreach ($usuarios as $key => $value) { ?>
                <tr id="tr_<?=$value['id']?>">
                    <td><?=$value['cedula']?></td>
                    <td><?=$value['nombres']?></td>
                    <td><?=$value['apellidos']?></td>
                    <td><?=$value['email']?></td>
                    <td><?=$value['edad']?></td>
                    <td><?=$value['nacimiento']?></td>
                    <td>
                        <a class="btn btn-sm  btn-info" type="button" href="javascript:showDetail(<?=$value['id']?>)">
                            <span class="fa fa-edit"></span>
                        </a>
                        <a class="btn btn-sm  btn-danger" type="button" href="javascript:destroy(<?=$value['id']?>)">
                            <span class="fa fa-trash"></span>
                        </a>
                    </td>
                </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
 
<script>


function calcularEdad(){
    var hoy = new Date();
    var cumple = new Date($('#nacimiento').val());
    var edad = hoy.getFullYear() - cumple.getFullYear();
    var m = hoy.getMonth() - cumple.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumple.getDate())) {
        edad--;
    }
    $('#edad').val(edad);
}

/**
 */
function showDetail( id ){
	$.ajax({
		url : "/usuarios/find/" + id + '/?nocache=' + Math.random(),
		type : "GET",
		dataType : "json",		 
		success : function(data) {
	    	if( data.status == 200 ){
	    		$.each( data.response, function( index, item ){	    			
	    			$("#"+index).val(item);	    			
	    		});
				$("#modalDetail").modal("show");		
			} else {					
				alert('Usuario no encontrado');
			}
        }
    });
}


/**
 * Undocumented function
 *
 * @return void
 */
function create(){
    
    $.each( $(':input'), function( index, item ){	    			
        $(item).val("");	    			
    });
    $('.form-group').removeClass('has-error');
    $('#id').val('-1');
    $("#modalDetail").modal("show");
    $('cedula').focus();
}


/**
 * Undocumented function
 *
 * @return void
 */
function store(){

    $('.form-group').removeClass('has-error');

    id = $('#id').val();

    if( id <= -1 ){
		ajaxType = "POST";
	} else {
		ajaxType = "PUT";
	}

	$.ajax({
		url : '/usuarios/',
		data : {
            id : $('#id').val(),
            cedula : $('#cedula').val(),
            email : $('#email').val(),
            nombres : $('#nombres').val(),
            apellidos : $('#apellidos').val(),
            nacimiento : $('#nacimiento').val(),
            edad : $('#edad').val(),
        },
		type : ajaxType,
		dataType : "json",		 
		success : function(data) {

			//Created - Creado con exito
	    	if( data.status == 201 ){

	            if (ajaxType == "PUT"){
					var row = $("#tr_" + data.response.id);
					_dataTable.row(row).remove().draw();
				}

                var _rowNode = _dataTable.row.add([
                    data.response.cedula,
                    data.response.nombres,
                    data.response.apellidos,                    
                    data.response.email,         
                    data.response.edad,
                    data.response.nacimiento,                    
                    '<a class="btn btn-sm btn-info" type="button" href="javascript:showDetail('+data.response.id+')">'+
                            '<span class="fa fa-edit"></span></a> '+
                          '<a class="btn btn-sm  btn-danger" type="button" href="javascript:destroy('+data.response.id+')">'+
                            '<span class="fa fa-trash"></span></a>'
                ]).draw().node();
				$(_rowNode).attr("id", "tr_" + data.response.id);


                $("#modalDetail").modal("hide");

	    	} else if( data.status == 400 ){
				alert('El campo '+data.response.text+'es obligatorio. Complete todos los campos para continuar');
                $('#'+data.response.field).parent().addClass('has-error');
                $('#'+data.response.field).focus();
	    	} else {
			
			}
	    }
	});
}


function destroy( id ){

    if (confirm("¿Confirma que desea eliminar el registro? Esta operación no se puede reversar")) {
        $.ajax({
            url : '/usuarios/'+id,
            type : 'DELETE',
            dataType : "json",		 
            success : function(data) {
                if( data.status == 201 ){
                    _dataTable.row("#tr_"+id).remove().draw( false );
                    alert('Registro elimnado con éxito');                    
                } else if( data.status == 400 ){
                    alert('Error');
                } else {
                
                }
            }
        });
    }
}

var _dataTable;

$(document).ready(function(){
    _dataTable = $('#example').DataTable({
        "language": {
            "info": "Registros <strong>_START_</strong> al <strong>_END_</strong> de un total de <strong>_TOTAL_</strong> registros",
            "infoFiltered": " - filtrado de _MAX_ registros",
            "processing": "Procesando...",
            "search": "Filtrar: ",
            "sEmptyTable": "Sin datos para mostrar...",
            "sLoadingRecords": "Cargando...",
			"loadingRecords": "Cargando...",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sZeroRecords": "No se encontraron resultados",
            "sSearchPlaceholder": "Filtar resultados...",
            "sDecimal": ",",
            "sInfoThousands": ",",
            "searchDelay": 100,
            "lengthMenu": 'Mostrar <select class="form-control select2" style="width:auto !important;">' +
                '<option value="10">10</option>' +
                '<option value="25">25</option>' +
                '<option value="50">50</option>' +
                '<option value="-1">Todos</option>' +
                '</select> registros',
            "paginate": {
                "sFirst": '<span class="fa fa-square-o-left"></span>',
                "sLast": '<span class="fa fa-square-o-right"></span>',
                "sNext": '<span class="fa fa-caret-right"></span>',
                "sPrevious": '<span class="fa fa-caret-left"></span>'
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
	$("#btnCreate").click(create);
    $("#btnStore").click(store);
    $("#nacimiento").change(calcularEdad)
});	


</script>




</body>