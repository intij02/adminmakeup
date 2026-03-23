<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reporte Pagos día <?php echo date('d/m/y', strtotime($date)); ?></title>
	<link href="/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<h2>Reporte Pagos [ <?php echo date('d/m/y', strtotime($date)); ?> ]</h2>
			</div>
		</div>
		<div class="row">
			<table class="table table-sm table-bordered">
				<tr>
					<th>Folio</th>
					<th>Cliente</th>
					<th>Hora</th>
					<th>Fecha</th>
					<th>Cta.</th>
					<th>Total</th>
					<th class="text-center">Rev</th>
					<th class="text-center">Imp</th>
					<th class="text-center">Guía</th>
				</tr>
				<?php foreach($pagos as $pago): ?>
				<tr>
					<td><?= esc($pago['id_cot']) ?></td>
					<td class="text-capitalize"><?php if($pago['nombre_cliente'] != ''){ echo strtolower($pago['nombre_cliente']); }else{ echo 'Tel: '.substr($pago['telefono'], 3); } ?></td>
					<td><?= esc(date('h:i A', strtotime($pago['hora']))) ?></td>
					<td><?= esc(date('d/m/y', strtotime($pago['fecha_pago']))) ?></td>
					<td><?= esc($pago['cta']) ?></td>
					<td><?= esc(number_format($pago['total_cotizacion'])) ?></td>
					<td class="text-center">
					<?php if($pago['verificado']){ ?>
						<div class="fs-4">•</div>
					<?php } ?>
					</td>
					<td class="text-center">
						<?php if($pago['visto']){ ?>
						<div class="fs-4">•</div>
						<?php } ?>
					</td>
					<td class="text-center">
						<?php if($pago['guia']){ ?>
						<div class="fs-4">•</div>
						<?php } ?>
					</td>
				</tr>
				<?php endforeach ?>
			</table>
		</div>
	</div>
	<script src="/js/jquery.min.js"></script>

	<script>
		$(function(){
			window.print();
		})
	</script>
</body>
</html>