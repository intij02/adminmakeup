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

	<div class="container">
		<di class="row my-2 justify-content-end">
			<div class="col-md-3">
				<div class="input-group mb-3 shadow-sm">
					<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-calendar-days fa-lg"></i></span>
					<input type="text" class="form-control" placeholder="Buscar Fecha: Año-Mes-Día" autocomplete="off" aria-label="fecha" aria-describedby="basic-addon1" id="datepk">
				</div>
			</div>
		</di>
		<div class="row my-1">
			<div class="col-md">
				<div class="card shadow-sm border-light">
					<div class="card-header fs-4">
						<div class="row justify-content-between">
							<div class="col">
								Cotizaciones del día: [ <?php echo date('d/m/y', strtotime($date)) ?> ]
							</div>
							<div class="col-4 d-grid">
								<?php if(check_permisos('admin') || check_permisos('super')){ ?>
								<button type="button" class="btn btn-outline-danger btn-sm " id="btnDelete">
											Eliminar seleccionados
										</button>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="loading text-center d-none">
							<table class="table">
								<thead>
									<tr>
										<th class="text-left">Pago</th>
										<th>Folio</th>
										<th>Cliente</th>
										<th>Hora</th>
										<th>Total</th>
										<th>Tipo</th>
										<th>Borrar</th>
									</tr>
								</thead>
								<tr>
									<td class="placeholder-glow"><span class="placeholder w-100"></span></td>
									<td class="placeholder-glow"><span class="placeholder w-100"></span></td>
									<td class="placeholder-glow"><span class="placeholder w-100"></span></td>
									<td class="placeholder-glow"><span class="placeholder w-100"></span></td>
									<td class="placeholder-glow"><span class="placeholder w-100"></span></td>
									<td class="placeholder-glow"><span class="placeholder w-100"></span></td>
									<td class="placeholder-glow"><span class="placeholder w-100"></span></td>
								</tr>
							</table>
						</div>
						<div class="table-responsive data">
							<form action="/cotizaciones/borrar_lista" method="post">
							<table class="table" id="tableData">
								<thead>
									<tr>
										<th class="text-left">Pago</th>
										<th>Folio</th>
										<th>Cliente</th>
										<th>Hora</th>
										<th>Total</th>
										<th>Tipo</th>
										<?php if(check_permisos('admin') || check_permisos('super')){ ?>
										<th>Borrar</th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($cotizaciones as $cot): ?>
									<tr id="<?= esc($cot['id']); ?>">
										<td class="text-left"><?php if($cot['pago_recibo']){ ?><button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#recibo" data-recibo="<?= esc($cot['pago_recibo']) ?>"><i class="fa-solid fa-receipt"></i></button><?php } ?></td>
										<td><a href="/cotizaciones/editar_cot/<?= esc($cot['id']); ?>" target="_blank"><?= esc($cot['id']); ?></a></td>
										<td class="text-capitalize"><?php if($cot['cliente_nombre'] != ''){ echo strtolower($cot['cliente_nombre']); }else{ echo 'Tel: '.substr($cot['cliente_tel'], 3); } ?></td>
										<td><?= esc(date('H:i', strtotime($cot['hora']))); ?></td>
										<td><?= esc(number_format($cot['total'])); ?></td>
										<td><?php if(!$cot['entienda']){ echo '<span class="text-primary-emphasis">Web</span>'; }else{ echo '<span class="text-primary">En Tienda</span>'; } ?></td>
										<?php if(check_permisos('admin') || check_permisos('super')){ ?>
										<td>
											<?php if(!$cot['pago_recibo']){ ?>
											<input type="checkbox" name="ids[]" value="<?= esc($cot['id']) ?>" class="form-check-input border-danger">
											<?php } ?>
										</td>
										<?php } ?>
									</tr>
									<?php endforeach ?>
								</tbody>
							</table>
							</form>
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
        		<div class="col"><img src="" id="recibo-ver" class="w-100"></div>
        	</div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="resp_borrar" tabindex="-1" aria-labelledby="resp_borrarLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="reciboLabel">Resumen de productos en Existencia</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<div id="resultado"></div>
      </div>
    </div>
  </div>
</div>
<script>
$('#recibo').off('show.bs.modal').on('show.bs.modal', function (event) {
  	let button = $(event.relatedTarget) ;
  	let recibo = button.data('recibo');
	$('#recibo-ver').attr('src', '/pagos/ver/'+recibo);
})
$('#recibo').off('hide.bs.modal').on('hide.bs.modal', function (event) {
	$('#recibo-ver').attr('src', '');
})
	$(function(){
		$( "#datepk" ).datepicker({
	      dateFormat: "yy-mm-dd"
		});
      $("#tableData").DataTable(
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
	})
$('#datepk').change(function(event) {
	$('.data').addClass('d-none')
	$('.loading').removeClass('d-none')
    let fSelect = $('#datepk').val();
	window.location.href = "/cotizaciones/fecha/"+fSelect
});
</script>
<script>

document.getElementById('btnDelete').addEventListener('click', function() {
    const ids = Array.from(document.querySelectorAll('.form-check-input:checked'))
                     .map(cb => cb.value);

    if (ids.length === 0) {
        alert('No seleccionaste ninguna cotización.');
        return;
    }

    if (!confirm('¿Seguro que deseas eliminar las cotizaciones seleccionadas?')) return;

    fetch("<?= site_url('cotizaciones/borrar_lista') ?>", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
        	new bootstrap.Modal(document.getElementById('resp_borrar')).show()
            document.getElementById('resultado').innerHTML = `
                <div class="alert alert-success">${data.message}</div>
                <table class="table table-sm table-striped table-bordered"><thead><tr><th>Producto</th><th class="text-end">Cantidad</th><th class="text-end">Antes</th><th class="text-end">Ahora</th><tbody>${data.reporte.map(r => `<tr><td>${r.nombre}</td><td class="text-end">${r.cantidad}</td><td class="text-end">${r.antes}</td><td class="text-end">${r.ahora}</td>`).join('')}</tr>
            `;

            // Opcional: eliminar filas eliminadas de la tabla
            ids.forEach(id => {
                const row = document.querySelector(`.form-check-input[value="${id}"]`).closest('tr');
                if (row) row.remove();
            });
        } else {
        	new bootstrap.Modal(document.getElementById('resp_borrar')).show()
            document.getElementById('resultado').innerHTML = `
                <div class="alert alert-danger">${data.message}</div>
            `;
        }
    })
    .catch(err => console.error(err));
});
</script>
</body>
</html>