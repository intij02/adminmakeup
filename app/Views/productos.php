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
		<div class="row my-3">
			<div class="col-md">
				<div class="card shadow-sm border-light">
					<div class="card-header">
						Base General de productos
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Producto</th>
										<th>Precio P.</th>
										<th>Existencia</th>
										<th>Peso</th>
										<th>Opciones</th>
										<th>Código Barras</th>
									</tr>
								</thead>
								<tbody class="d-none lista">
									<?php foreach ($productos as $prod): ?>
									<tr>
										<td><?php echo $prod['descripcion']; ?></td>
										<td><?php echo number_format($prod['precio_p']); ?></td>
										<td><?php echo $prod['existencia']; ?></td>
										<td><?php echo $prod['peso'] ?></td>
										<td>
											<a href="/productos/editar_producto/<?php echo $prod['id']; ?>" class="btn btn-secondary btn-sm">Editar</a>
											
										</td>
										<td><button data-bs-toggle="modal" data-bs-target="#codigoBarras" class="btn btn-secondary" codigo="<?php echo url_title($prod['codigo']); ?>"><i class="fa-solid fa-barcode fa-xl"></i></button></td>
									</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>
<div class="modal fade" id="codigoBarras" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="codigoBarrasLabel"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="" alt="" id="imagenCodBar" download="">
      </div>
    </div>
  </div>
</div>

<script>
	const codigoBarras = document.getElementById('codigoBarras')

	codigoBarras.addEventListener('shown.bs.modal', event => {
		const button = event.relatedTarget
		const recipient = button.getAttribute('codigo')
		const codigo = recipient;
	  $.get('/codigob/'+codigo, function(data) {
	  	const imagen = 'data:image/png;base64,'+data;
	  	$('#imagenCodBar').attr('src', imagen);
	  	$('#imagenCodBar').attr('download', codigo+'.png');
	  	$('#codigoBarrasLabel').html(codigo)
	  });
	})
</script>
<script>
	$(function(){
      $("table").DataTable(
      	{"order": [[ 2, "desc"]],
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