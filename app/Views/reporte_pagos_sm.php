<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ERP Centro de Mayoreo</title>
<?php echo view('ext/links'); ?>
</head>
<body>

	<div class="container">
		<div class="row my-3">
			<div class="col-md">
				<div class="card shadow">
					<?php if($pagos){ ?>
					<div class="card-header">
						<a href="/reportes/imprimirReporte/<?php echo $f; ?>/<?php echo $cuenta_S; ?>"  class="btn btn-secondary d-block ml-auto">Descargar PDF</a>
					</div>
					<?php } ?>
					<div class="card-body">
						<form method="post" action="<?php echo current_url(); ?>">
							<div class="row">
								<div class="col">
									<label>Seleccionar Fecha:</label>
									<input type="text" id="datepk" class="form-control" placeholder="Ingresa la fecha a filtrar" autocomplete="off">
									<input type="hidden" id="fecha_select" name="fecha_select" required>
								</div>
								<div class="col">
									<label>Selecciona la cuenta</label>
									<select name="cta" class="form-control form-select" style="text-transform: capitalize;">
										<?php foreach ($cuentas as $cta): ?>
											<option value="<?php echo $cta['cuenta'] ?>"><?php echo $cta['cuenta']; ?> -> <?php echo strtolower($cta['nombre']); ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="row my-2 justify-content-start">
								<div class="col-4"><button type="submit" class="btn btn-primary">Filtrar</button></div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php if($pagos){ ?>
		<div class="row my-2">
			<div class="col">
				<div class="card">
					<div class="card-header"><b>Lista de pagos del día</b> [ <?php echo date('d/m/y', strtotime($f)); ?> ] <b>Cuenta:</b> <?php echo $cuenta_S.' -> '.$cuenta_Nom ?></div>
					<div class="card-body">
						<div class="table-responsive-sm">
						<table class="table tbl table-sm table-bordered">
							<thead>
								<tr>
									<th>Folio</th>
									<th class="text-right">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php $suma = 0;  foreach ($pagos as $pago): ?>
									<?php foreach ($cots as $cot) {
										if($pago['id_cot'] == $cot['id']){
											$total = $cot['total'];
											break;
										}
									} ?>
								<tr>
									<td><?php echo $pago['id_cot']; ?></td>
									<td class="text-right"><?php echo number_format($total); ?></td>
								</tr>
								<?php $suma = $suma + $total; endforeach; ?>
							</tbody>
						</table>
						</div>
						<h3 align="right">Total: <?php echo number_format($suma); ?></h3>
					</div>
				</div>
			</div>
		</div>
		<?php }else{ echo ""; } ?>
	</div>
<?php echo view('ext/footer'); ?>
	<script>
		$(function(){
			$( "#datepk" ).datepicker({

		      dateFormat: "yy-mm-dd"

		    });
    	})
  $('#datepk').change(function(event) {

    var fSelect = $('#datepk').val();
    $('#fecha_select').val(fSelect);

  });
	</script>	

</body>
</html>