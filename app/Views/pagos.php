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
		<div class="row my-2 justify-content-between">
			<div class="col-md">
				<?php if(check_permisos('admin') || check_permisos('super')){ ?>
					<a href="/pagos/print_pagos/<?php echo $date ?>" target="_blank" class="btn btn-secondary btn-sm"><i class="fa-solid fa-print"></i> Imprimir</a>
				<?php } ?>
				<buton class="btn btn-sm btn-secondary enTienda" onclick="buscarTiendaEnDatatable()">En Tienda</buton>
				<button class="btn btn-sm btn-primary enTiendaActivo d-none" onclick="resetBusqueda()">En Tienda</button>
				<span class="fs-5 fw-bold text-body-secondary">Cuentas:</span>
				<?php foreach ($cuentas as $cta): ?>
				<button class="btn btn-sm btn-secondary btn-cuenta" onclick="buscarEnDatatable(<?php echo $cta['cuenta']; ?>)"><?php echo $cta['cuenta']; ?></button>
				<?php endforeach ?>
			</div>
			<div class="col-md-3">
				<div class="input-group mb-3">
					<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-calendar-days fa-lg"></i></span>
					<input type="text" class="form-control" placeholder="Buscar Fecha: Año-Mes-Día" aria-label="fecha" autocomplete="off" aria-describedby="basic-addon1" id="datepk">
				</div>
			</div>
		</div>
		<div class="row my-3">
			<div class="col-md">
				<div class="card shadow border-light">
					<div class="card-header fs-4">
						Pagos del día: [ <?php echo date('d/m/y', strtotime($date)) ?> ]
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="tb" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th class="col-1"></th>
										<?php if(check_permisos('admin') || check_permisos('super')){ ?>
										<th>Borrar</th>
										<?php } ?>
										<th class="col-1 text-center">Cotización</th>
										<th>Cliente</th>
										<th>Hora</th>
										<th>Fecha</th>
										<th>Cuenta</th>
										<th>Total</th>
										<th>Tienda</th>
										<th class="col-1 text-center">Recibo</th>
										<th class="text-center">Rev.</th>
										<th class="text-center">Imp.</th>
										<th class="text-center">Guia</th>
										<th class="text-center">Aten.</th>
									</tr>
								</thead>
								<tbody>
								<?php $num = 1;  foreach($pagos as $pago): ?>
									<tr>
										<td class="text-secondary"><?= esc($pago['pago_id']) ?></td>
											<?php if(check_permisos('admin') || check_permisos('super')){ ?>
											<td class="text-center">
												<a href="/pagos/borrar_pago/<?= esc($pago['pago_id']) ?>/<?php echo $date ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de Borrar el pago?')"><i class="fa-solid fa-trash-can"></i></a>
											</td>
											<?php } ?>
										<td><a href="/cotizaciones/editar_cot/<?= esc($pago['id_cot']); ?>" target="_blank"><?= esc($pago['id_cot']) ?></a></td>
										<td class="text-capitalize"><?php if($pago['nombre_cliente'] != ''){ echo strtolower($pago['nombre_cliente']); }else{ echo 'Tel: '.substr($pago['telefono'], 3); } ?></td>
										<td><small><?= esc(date('h:i A', strtotime($pago['hora']))) ?></small></td>
										<td><small><?= esc(date('d/m/y', strtotime($pago['fecha_pago']))) ?></small></td>
										<td><?= esc($pago['cta']) ?></td>
										<td><?= esc(number_format($pago['total_cotizacion'])) ?></td>
										<td>
											<?php if($pago['tienda']){ ?><span class="text-primary">En Tienda</span><?php } ?>
										</td>
										<td class="text-center"><button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#recibo" data-recibo="<?= esc($pago['recibo']) ?>" data-id="<?= esc($pago['pago_id']) ?>" data-cliente="<?php if($pago['nombre_cliente'] != ''){ echo strtolower($pago['nombre_cliente']); }else{ echo 'Tel: '.substr($pago['telefono'], 3); } ?>" data-folio="<?= esc($pago['id_cot']) ?>" data-total="<?= esc(number_format($pago['total_cotizacion'])) ?>"><i class="fa-solid fa-receipt"></i></button></td>
										<td class="text-center">
											<?php if($pago['verificado']){ ?>
												<button class="btn btn-sm rounded-circle" style="background-color: #5de102 !important; width: 25px; height: 25px;"> </button>
											<?php } ?>
											</td>
										<td class="text-center"><?php if($pago['visto']){ ?>
												<button class="btn btn-sm rounded-circle" style="background-color: #ff00d7 !important; width: 25px; height: 25px;"> </button>
											<?php } ?></td>
										<td class="text-center"><?php if($pago['guia']){ ?>
												<button class="btn btn-sm rounded-circle" style="background-color: #0058ff !important; width: 25px; height: 25px;"> </button>
											<?php } ?></td>
										<td class="text-center"><?php if($pago['atencion']){ ?>
												<button class="btn btn-sm rounded-circle bt-atencion" data-bs-toggle="modal" data-bs-target="#atencion" data-idpago="<?php echo $pago['pago_id'] ?>" data-notas="<?php echo $pago['notas'] ?>" style="background-color: #fb0202 !important; width: 25px; height: 25px;"> </button>
											<?php } ?></td>
									</tr>
								<?php $num++; endforeach; ?>
								</tbody>		
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>
<div class="modal fade" id="recibo" tabindex="-1" aria-labelledby="reciboLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="reciboLabel">Detalle Recibo</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        	<div class="row">
        		<div class="col-4">
        			<div class="mb-2">
        				<div><b>Cliente: </b> <span id="clienteNom" class="elinkTx text-capitalize"></span></div>
        				<div><b>Folio: </b> <span id="folioRev" class="elinkTx"></span></div>
        				<div><b>Total: </b> <span id="totalRev" class="elinkTx"></span></div>
        			</div>
        			<div class="d-grid gap-2">
	        			<a href="" class="btn btn-sm mb-1 elink" id="lmImpreso" style="background-color: #ff00d7 !important; color: white">Marcar Impreso</a>
	        			<a href="" class="btn btn-sm mb-1 elink" id="lmGuia" style="background-color: #0058ff !important; color: white">Marcar Guía</a>
	        			<a href="" class="btn btn-sm mb-1 elink" id="lmVerif" style="background-color: #5de102 !important; color: white">Pago Revisado</a>
	        			<a href="" class="btn btn-sm mb-1 elink" id="lmAtencion" style="background-color: #fb0202 !important; color: white">Atención</a>
	        			<a href="" class="btn btn-sm mb-1 elink" id="lmQatencion" style="background-color: #cc157c !important; color: white;">Quitar Atención</a>        				
        			</div>
        		</div>
        		<div class="col"><img src="" id="recibo-ver" class="w-100"></div>
        	</div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="atencion" tabindex="-1" aria-labelledby="atencionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="atencionModalLabel">Atención</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="atencion"></div>
        <form action="/pagos/atencion" method="post" id="fmatencion">
        	<div class="row">
        		<div class="col">
        			<textarea name="notas" id="notas" class="form-control"></textarea>
        		</div>
        	</div>
        	<input type="hidden" name="fecha" value="<?php echo $date; ?>">
        	<input type="hidden" name="id_pago" id="id_pago">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary send-notas"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
      </div>
    </div>
  </div>
</div>
<script>
$('.send-notas').click(function(event) {
			var text = $('#notas').val()
			if(text == ""){
				$('#notas').focus()
				return false
			}else{
				$('#fmatencion').submit();
			}
});
$('#atencion').off('show.bs.modal').on('show.bs.modal', function (event) {
  	let button = $(event.relatedTarget) ;
  	let notas = button.data('notas');
  	let id_pago = button.data('idpago');
  	$('#notas').val(notas)
  	$('#id_pago').val(id_pago)
})
$('#atencion').off('hide.bs.modal').on('hide.bs.modal', function (event) {
	$('#notas').val('')
  $('#id_pago').val('')
})
$('#datepk').change(function(event) {
    let fSelect = $('#datepk').val();
	window.location.href = "/pagos/fecha/"+fSelect
});
$('.btn-cuenta').click(function(event) {
	$('.btn-cuenta').removeClass('btn-primary')
	$('.btn-cuenta').addClass('btn-secondary')
	$(this).removeClass('btn-secondary')
	$(this).addClass('btn-primary')
});
function resetBusqueda() {
		$('.enTiendaActivo').addClass('d-none')
		$('.enTienda').removeClass('d-none')
	var table = $('#tb').DataTable();
	table.column(8).search('').draw();
}
function buscarEnDatatable(terminoDeBusqueda) {
    var table = $('#tb').DataTable(); // Asegúrate de que este es el ID correcto de tu DataTable
    //table.search(terminoDeBusqueda).draw(); // Realiza la búsqueda y redibuja la tabla
    table.column(6).search(terminoDeBusqueda).draw();
}
function buscarTiendaEnDatatable() {
		$('.enTienda').addClass('d-none')
		$('.enTiendaActivo').removeClass('d-none')
    var table = $('#tb').DataTable(); // Asegúrate de que este es el ID correcto de tu DataTable
    //table.search(terminoDeBusqueda).draw(); // Realiza la búsqueda y redibuja la tabla
    table.column(8).search('En Tienda').draw();
}

$('#recibo').off('show.bs.modal').on('show.bs.modal', function (event) {
  	let button = $(event.relatedTarget) ;
  	let recibo = button.data('recibo');
  	let id = button.data('id');
  	let cliente = button.data('cliente')
  	let folio = button.data('folio')
  	let total = button.data('total')
	$('#recibo-ver').attr('src', '/pagos/ver/'+recibo);

	$('#clienteNom').html(cliente)
	$('#folioRev').html(folio)
	$('#totalRev').html(total)
    $('#lmVerif').attr('href', '/pagos/upd_verifP/'+id+'/'+'<?php echo $date; ?>');
    $('#lmImpreso').attr('href', '/pagos/upd_impreso/'+id+'/'+'<?php echo $date; ?>');
    $('#lmGuia').attr('href', '/pagos/upd_guia/'+id+'/'+'<?php echo $date; ?>');
    $('#lmAtencion').attr('href', '/pagos/upd_atencion/'+id+'/'+'<?php echo $date; ?>');
    $('#lmQatencion').attr('href', '/pagos/upd_atencion_q/'+id+'/'+'<?php echo $date; ?>');
})
$('#recibo').off('hide.bs.modal').on('hide.bs.modal', function (event) {
	$('#recibo-ver').attr('src', '');
	$('.elink').attr('href', '');
	$('.elinkTx').html('')
})
	$(function(){
		$( "#datepk" ).datepicker({
	      dateFormat: "yy-mm-dd"
		});
      $("table").DataTable(
      	{"order": [[ 0, "desc"]],
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
      $('.lista').removeClass('d-none')
	})
</script>
</body>
</html>