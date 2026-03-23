						<div class="table-responsive">
						<table id="tabledata" class="table table-striped">
							<thead>
								<tr class="table-secondary">
									<th class="col-1">Cantidad</th>
									<th>Descripción</th>
									<th class="text-end col-1">Peso</th>
									<th class="col-1">Precio</th>
									<th class="col-1">Monto</th>
									<th class="col-1">Editar</th>
								</tr>
							</thead>
						</table>
						</div>
								<?php $pesoSum = 0; $total = 0; $monto = 0; foreach ($prodsCot as $prod): if($prod['cantidad']>0){ ?>
								<?php $id_prod = $prod['id_prod'];
									$productoData = array_filter(session()->get('productosApp'), function($producto) use ($id_prod) {
            							return $producto['id'] == $id_prod;
            						});

									$productoData = array_values($productoData);
									if($productoData){$productoData = $productoData;}else{$productoData = null;}
								?>
						<form>
						<div class="row py-2 my-1 border-bottom ">
							<div class="col-md-1">
								<input type="tel" name="cantidad" value="<?php echo $prod['cantidad']; ?>" class="form-control form-control-sm">
							</div>
							<div class="col-md">
								<?php echo $productoData[0]['descripcion']; ?>
							</div>
							<div class="col-md-1 text-end">
								<?php $peso = (float)$productoData[0]['peso'] * (int)$prod['cantidad']; echo $peso; ?> kg
							</div>
							<div class="col-md-1">
								<input type="text" name="precio" value="<?php echo $prod['precio']; ?>" class="form-control form-control-sm">
							</div>
							<div class="col-md-1">
								<?php $monto = $prod['cantidad'] * $prod['precio']; echo number_format($monto)  ?>
							</div>
							<div class="col-md-1">
								<button class="btn btn-sm btn-danger erase" id="<?php echo $prod['id']; ?>" type="button"><i class="fa-solid fa-trash-can"></i></button>
										<button class="btn btn-sm btn-primary update" type="button"><i class="fa-solid fa-pencil"></i></button>
										<input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
										<input type="hidden" name="id_cot" value="<?php echo $prod['id_cot']; ?>">
							</div>
						</div>
						</form>
						<?php $total = $monto + $total; $pesoSum = $pesoSum + $peso; } endforeach; ?>
<script>
	function updateTable(){
		$.get('/cotizaciones/getDataProds/<?php echo $id_cot; ?>', function(data) {
			$('.table-data').html(data)
		});
	}
	$(function(){
		$('.dataLoad').removeClass('placeholder w-50')
		$('#total').html('<?php echo number_format($total) ?>')
		$('#peso').html('<?php echo $pesoSum; ?>')
	})
	$('.erase').click(function(event) {
		let idRow = $(this).attr('id');
		$.post('/cotizaciones/borrar_row', {id: idRow, id_cot: <?php echo $id_cot; ?>}, function(data, textStatus, xhr) {
			updateTable()
		});
	});
	$('.update').click(function(event) {
		let form = $(this).closest('form');
		$.post('/cotizaciones/update_row', form.serialize(), function(data, textStatus, xhr) {
			updateTable()
		});
	});
</script>