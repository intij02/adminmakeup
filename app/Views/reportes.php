<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ERP Centro de Mayoreo</title>
<?php echo view('ext/links'); ?>
</head>
<body>
<?php echo view('ext/header'); ?>

	<div class="container-fluid">
	  <div class="row my-3">
	    <div class="col">
	      <ul class="nav">
	        <li class="nav-item m-1">
	        	<button class="btn btn-info text-light" data-bs-toggle="modal" data-bs-target="#fecha">Buscar por fecha</button>
	        </li>
	        <?php if(isset($fecha)){ ?>
	        <li class="nav-item m-1">
	        	<button class="btn btn-outline-secondary">Reporte del día: <?php echo date('d/m/y', strtotime($fecha)); ?></button>
	        </li>
	      	<?php } ?>
	      </ul>
	    </div>
	  </div>
		<div class="row my-3">
			<div class="col-md">
				<div class="card shadow-sm border-light">
					<div class="card-header">
						Reportes
					</div>
					<div class="card-body">
						<div class="load p-2 text-center">
							Cargando...
						</div>
						<div class="tbCont d-none">
							<div class="table-responsive">
								<table class="table table-striped table-sm tbcots" style="width:100%">
	              <thead>
	                <tr>
	                  <th>Producto</th>
	                  <th>Cantidad</th>
	                  <th>Existencia</th>
	                  <th>Limite</th>
	                  <th>Agotado</th>
	                  <th>Editar</th>
	                </tr>
	              </thead>
									<tbody id="contList">
										
									</tbody>
								</table>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<div class="modal fade" id="fecha" tabindex="-1" aria-labelledby="fecha" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="fecha">Buscar por fecha</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/reportes" id="fm-buscar" method="post">
          <input type="text" id="fechapk" class="form-control" placeholder="Fecha">
          <input type="hidden" name="fecha" id="fechaSelect">
        </form>
      </div>
      <div class="modal-footer">
				<button type="button" class="btn btn-primary irFecha">Buscar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editarP" tabindex="-1" aria-labelledby="editarP" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editarP">Editar existencia producto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<p>Producto: <span id="descProd"></span></p>
        <form action="" id="fm-edit" method="post">
          <div class="form-grouop my-3">
          	<label for="existencia">Existencia:</label>
            <input type="number" min="1" id="existencia" name="existencia" class="form-control" placeholder="Existencia">
          </div>
          <div class="form-grouop my-3">
          	<label for="limite">Limite:</label>
            <input type="number" min="1" id="limite" name="limite" class="form-control" placeholder="Limite por producto">
          </div>
          <input type="hidden" name="id" id="idProducto">
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-info bt-agotado" type="button">Agotado</button>
        <button type="button" class="btn btn-primary bt-update"><i class="fas fa-pencil-alt"></i> Actualizar</button>
      </div>
    </div>
  </div>
</div>

<?php echo view('ext/footer'); ?>
<script>
  $(function(){
    $('#contList').load('/reportes/lista_reporte/<?php if(isset($fecha)){ echo $fecha; } ?>', function(){
      $(".tbcots").DataTable(
      	{"order": [[ 1, "desc"]],
      	"pageLength": 100,
				"language": {
					        "decimal": "",
					        "emptyTable": "No hay información",
					        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
					        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
					        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
					        "infoPostFix": "",
					        "thousands": ",",
					        "lengthMenu": "Mostrar _MENU_ Entradas",
					        "loadingRecords": "Cargando...",
					        "processing": "Procesando...",
					        "search": "Buscar:",
					        "zeroRecords": "Sin resultados encontrados",
					        "paginate": {
					            "first": "Primero",
					            "last": "Ultimo",
					            "next": "Siguiente",
					            "previous": "Anterior"
					        }
					    },
      });
      $('.load').addClass('d-none');
      $('.tbCont').removeClass('d-none');
    });
    $( "#fechapk" ).datepicker({
      dateFormat: "yy-mm-dd"
    });
  });
  var editarP = bootstrap.Modal.getOrCreateInstance($('#editarP'));

  $('.bt-agotado').click(function(){
      $('.tbCont').addClass('d-none');
      $('.load').removeClass('d-none');
      $('#contList').html('');
      form = $('#fm-edit').serialize();
	    $.ajax({
	      url: '/reportes/edit_agotado',
	      type: 'POST',
	      data: form,
	    })
	    .done(function(data) {
        	/*$('#contList').load('/reportes/lista_reporte/<?php if(isset($fecha)){ echo $fecha; } ?>', function(){
	          $('.load').addClass('d-none');
	          $('.tbCont').removeClass('d-none');
	          $('#idProducto').val('');
	          $('#existencia').val('');
	      		editarP.hide();
					});*/
	    		window.location.href = "/reportes/fecha/<?php if(isset($fecha)){ echo $fecha; } ?>"
    })
    .fail(function(data) {
      console.log(data);
    })    
  })
  $('.bt-update').click(function(){
    if($('#existencia').val() == ""){
      $('#existencia').focus();
    }else{
      $('.tbCont').addClass('d-none');
      $('.load').removeClass('d-none');
      form = $('#fm-edit').serialize();
      editarP.hide();
      $('#contList').html('');
      $.ajax({
        url: '/reportes/edit_prod',
        type: 'POST',
        data: form,
      })
      .done(function(data) {
        /*$('#contList').load('/reportes/lista_reporte/<?php if(isset($fecha)){ echo $fecha; } ?>', function(){
          $('.load').addClass('d-none');
          $('.tbCont').removeClass('d-none');
          $('#idProducto').val('');
          $('#existencia').val('');
          $('limite').val('');
        });*/
        window.location.href = "/reportes/fecha/<?php if(isset($fecha)){ echo $fecha; } ?>"
      })
      .fail(function() {
        console.log("error");
      })      
    }
  });
  $('#fechapk').change(function(event) {
    var fSelect = $('#fechapk').val();
    $('#fechaSelect').val(fSelect);
  });
  $('.irFecha').click(function(event) {
    var fecha = $('#fechaSelect').val();
    if(fecha == ""){
      $('#fechapk').focus()
    }else{
      window.location.href = "/reportes/fecha/"+fecha;
      //$('#fm-buscar').submit();
    }
  });
  $('#editarP').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('info') // Extract info from data-* attributes
    $('#idP').val(recipient);
    $.getJSON('/reportes/get_data_exis/'+ recipient, function(data) {
    	$('#existencia').val(data.existencia)
    	$('#limite').val(data.limite)
    	$('#descProd').html(data.descripcion)
    	$('#idProducto').val(data.id)
    });
  })
	function removeAllAttributes(element) {
				$('.modal-backdrop').remove();
				$('body').removeClass('modal-open');
				$('body').css('padding-right', '0px !important');
				$('body').css('overflow', 'auto !important');
        $.each(element[0].attributes, function() {
        	element.removeAttr(this.name);
  			});
  }

    $('#editarP').on('hide.bs.modal', function (event) {
    	$('#existencia').val('')
    	$('#limite').val('')
    	$('#descProd').html('')
    	$('#idP').val('');
    	removeAllAttributes($('body'))
  })
</script>
</body>
</html>