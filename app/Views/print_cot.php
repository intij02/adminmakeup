<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<title>ORDEN #<?php echo $cotizacion['id'] ?></title>
</head>
<body style="font-family: 'Verdana' !important;">
	<div class="container-fluid my-2">
		<div class="row">
			<div class="col">
				<div class="card border-light">
					<div class="card-header fs-5">
						<div class="row">
							<div class="col">
								Orden de pedido
							</div>
							<div class="col text-end">
								<?php echo $cotizacion['num'] ?>**
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col">
								<table class="table">
									<tr>
										<th>Cliente:</th>
										<?php if($clienteDir){ ?>
										<td><?php echo $clienteDir['recibe']; ?></td>
									<?php }else{ ?>
										<td><?php echo $cliente['nombre']; ?> [ Tel: ***<?php echo substr($cliente['telefono'],9); ?>]</td>
									<?php } ?>
									</tr>
									<tr>
										<th>Dirección:</th>
										<td>
											<?php if($cotizacion['entienda']){ echo "<h4>Recoge en Tienda</h4>"; }else{ ?>
											<?php if($clienteDir){ ?>

												 <?php echo $clienteDir['calle']; ?> <?php echo $clienteDir['numExt'] ?> - <?php echo $clienteDir['numInt']; ?>, Col. <?php echo $clienteDir['col']; ?>, <?php echo $clienteDir['del_mun']; ?>, <?php echo $clienteDir['estado']; ?> C.P. <?php echo $clienteDir['cp']; ?>

											<?php }else{ echo"SIN DATOS DE ENVIO"; } ?>
											<?php } ?>
										</td>
									</tr>
									<?php if(!$cotizacion['entienda']){ ?>
									<tr>
										<th>Teléfono:</th>
										<td><?php echo substr($cliente['telefono'], 3); ?></td>
									</tr>
									<?php } ?>
								</table>
							</div>
							<div class="col-2 text-center">
								<div class="fs-5">
									<div>#Folio</div>
									<div><?php echo $cotizacion['id'] ?></div>
								</div>
								<div class=""><?php echo date('d/m/y', strtotime($cotizacion['fecha'])); ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row my-2">
			<div class="col">
				<table class="table">
					<thead>
						<tr class="table-dark">
							<td class="text-center">Cantidad</td>
							<td>Descripción</td>
							<td>Codigo</td>
							<td>Precio</td>
							<td>Monto</td>
							<td>Imagen</td>
						</tr>
					</thead>
					<tbody>
								<?php $pesoSum = 0; $total = 0; $monto = 0; foreach ($prodsCot as $prod): if($prod['cantidad']>0){ ?>
								<?php
									$id_prod = $prod['id_prod'];
									$productoData = array_filter(session()->get('productosApp'), function($producto) use ($id_prod) {
            							return $producto['id'] == $id_prod;
            						});

									$productoData = array_values($productoData);
									if($productoData){$productoData = $productoData;}else{$productoData = null;}

									//imagenes
									$imagenData = array_filter($imagenes, function($imagen) use ($id_prod) {
            							return $imagen['id_prod'] == $id_prod;
            						});

									$imagenData = array_values($imagenData);
									if($imagenData){
										$imagenData = $imagenData;
										$imagen = '<img src="/cotizaciones/prod/'.$imagenData[0]['imagen'].'" height="105">';
									}else{
										$imagen = "Sin Imagen";
									}
								?>
						<tr>
							<td class="text-center fs-2"><b><?php echo $prod['cantidad']; ?></b></td>
							<td><?php echo $productoData[0]['descripcion']; ?></td>
							<td><?php echo $productoData[0]['codigo']; ?></td>
							<td>$<?php echo number_format($prod['precio'],2); ?></td>
							<td>$<?php $monto = $prod['cantidad'] * $prod['precio']; echo number_format($monto,2)  ?></td>
							<td><?php echo $imagen; ?></td>
						</tr>
					<?php $total = $monto+$total; } endforeach; ?>
					</tbody>
					<tr class="fs-4">
						<th colspan="4" class="text-end">Total:</th>
						<td><?php echo number_format($total); ?></td>
					</tr>		
				</table>
			</div>
		</div>
		<?php if($cotizacion['observaciones'] != ""){ ?>
		<div class="row">
			<div class="col">
				<h3>Observaciones:</h3>
			</div>
		</div>
		<div class="row">
			<div class="col p-3">
				<div class="card">
					<div class="card-header">Cliente:</div>
					<div class="card-body"><?php echo $cotizacion['observaciones']; ?></div>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if($seguimiento){ ?>
			<div class="row">
				<div class="col">
					<div class="card my-2">
						<div class="card-header">Personal:</div>
							<div class="card-body">
							<?php foreach($seguimiento as $seg): ?>
								<div class="border-bottom p-2 my-2">
								<div><?php echo $seg['texto']; ?></div>
								<div class="text-right p-1"><small>Fecha: <?php echo date('d-m-y', strtotime($seg['fecha'])); ?> / Hora: <?php echo date('H:i', strtotime($seg['hora'])); ?></small></div>
								</div>
							<?php endforeach;  ?>
							</div>
					</div>
				</div>
			</div>
		<?php } ?>
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-header">Observaciones PostVenta</div>
						<div class="card-body">
							<div class="my-2"><?php echo $texto_post['texto']; ?></div>
							<div class="my-2">
								<img src="https:https://centrodemayoreocdmx.com.mx/img/<?php echo $texto_post['imagen'] ?>" width="400" alt="">
							</div>
						</div>
					</div>
				</div>
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