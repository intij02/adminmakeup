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
		<div class="row my-2">
			<div class="col-md">
				<div class="row mb-2">
					<div class="col">
						<button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#mensajes"><i class="fa-solid fa-comments"></i> Enviar Mensaje</button>
						<button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#observaciones" data-id_cot="<?php echo $cotizacion['id']; ?>"><i class="fa-solid fa-pen-to-square"></i> Observaciones</button>
						<a href="/cotizaciones/print/<?php echo $cotizacion['id']; ?>" target="_blank" class="btn btn-secondary"><i class="fa-solid fa-print"></i> Imprimir</a>
						<button class="btn btn-success d-none btn-saldo" data-bs-toggle="modal" data-bs-target="#saldo">
							<i class="fa-regular fa-star"></i> Saldo a Favor
						</button>
					</div>
				</div>
				<div class="card shadow fs-5">
					<div class="card-header">
						<div class="row justify-content-between">
							<div class="col">Datos Cliente</div>
							<div class="col-1">
								<div class="d-grid d-gap">
									<button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#datosEnvio"><i class="fa-solid fa-user-pen"></i></button>
								</div>
							</div>
							<div class="col-3">
								<div class="d-grid d-gap">
									<button class="btn btn-info btn-sm" id="copyButton"><i class="fa-regular fa-copy"></i> Copiar Datos</button>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<?php if($clienteDir){ ?>
						<div class="copyContent"><b class="text-body-tertiary">Recibe:</b> <?php echo $clienteDir['recibe'] ?></div>
						<div><b class="text-body-tertiary">Dirección Envío:</b></div>
						<div class="copyContent"><?php echo $clienteDir['calle'] ?> <?php echo $clienteDir['numExt'] ?> <?php echo $clienteDir['numInt'] ?>, <?php echo $clienteDir['col'] ?>, <?php echo $clienteDir['del_mun'] ?>, <?php echo $clienteDir['estado'] ?>, C.P.: <?php echo $clienteDir['cp'] ?></div>
						<div class="copyContent"><b class="text-body-tertiary">Telefono:</b> <?php echo substr($cliente['telefono'], 3); ?></div>
					<?php }else{ ?>
						<div>Cliente: <?php echo $cliente['nombre'] ?> [ <?php echo substr($cliente['telefono'], 3); ?> ]</div>
						<div><b>Dir: Recoge en Tienda</b></div>
					<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-md">
				<div class="card shadow fs-5">
					<div class="card-header">
						Cotización
					</div>
					<div class="card-body placeholder-glow">
						<div class="mb-1 border-bottom"><b class="text-body-tertiary">Folio:</b> <?php echo $cotizacion['id']; ?></div>
						<div class="mb-1 border-bottom"><b class="text-body-tertiary">Fecha:</b> <?php echo date('d/m/y', strtotime($cotizacion['fecha'])); ?> - <b class="text-body-tertiary">Hora:</b> <?php echo date('h:i A', strtotime($cotizacion['hora'])); ?></div>
						<div class="mb-1 border-bottom"><b>Peso Total:</b> <span id="peso" class="placeholder w-50 dataLoad">1</span> kgs</div>
						<div class="mb-1 border-bottom"><b>Guías:</b> <?php if($descGuia){ echo $descGuia['descripcion']; } ?></div>
						<div class="mb-1 border-bottom">
							<form>
							<div class="input-group mb-1">
								<span class="input-group-text" id="inputGroup-sizing-default">Opcion:</span>
								<input type="text" class="form-control" name="num" id="num" value="<?php echo $cotizacion['num'] ?>">
								<button type="button" class="btn btn btn-secondary sendNum"><i class="fa-solid fa-pen-clip"></i></button>
							</div>
							<input type="hidden" name="id_cot" value="<?php echo $cotizacion['id']; ?>">
							</form>
						</div>
						<div class="fs-4 placeholder-glow">Total: $<span id="total" class="placeholder w-50 dataLoad"></span></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row my-1">
			<div class="col">
				<div class="card shadow">
					<div class="card-body">
						<form>
						<div class="row g-1 md-1">
							<div class="col-md-1"><div class="d-grid d-gap"><button class="btn btn-secondary upd-prods" type="button"><i class="fa-solid fa-arrows-rotate"></i> Productos</button></div></div>
							<div class="col-md-2 text-body-secondary fs-4">Agregar producto:</div>
							<div class="col-md"><input type="tel" name="cantidad" id="cantAdd" class="form-control" placeholder="Cantidad"></div>
							<div class="col-md"><input type="text" id="selectProd" class="form-control" placeholder="Producto"></div>
							<div class="col-md-2">
								<div class="d-grid d-gap">
									<button class="btn btn-secondary addProd" type="button"><i class="fa-solid fa-cart-plus"></i> Agregar</button>
								</div>
							</div>
						</div>
						<input type="hidden" name="id_cot" value="<?php echo $cotizacion['id']; ?>">
						<input type="hidden" name="id_prod" id="id_prod">
						</form>
						<div class="table-data my-1">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>
<div class="modal fade" id="observaciones" tabindex="-1" aria-labelledby="obslabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="obslabel">Observaciones</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="/cotizaciones/add_seguimiento">
      <div class="modal-body">
      		<div class="seguimiento"></div>
        	<textarea class="form-control" name="texto" id="textoObs"></textarea>
        	<input type="hidden" name="id_cot" value="<?php echo $cotizacion['id']; ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary sendObs"> Guardar</button>
      </div>
      <input type="hidden" name="url" value="<?php echo service('uri')->getPath() ?>">
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="mensajes" tabindex="-1" aria-labelledby="msjLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="msjLabel">Enviar mensaje al cliente:</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="/cotizaciones/nuevo_mensaje">
      <div class="modal-body">
        	<textarea class="form-control" name="texto" id="textoObsMsj"></textarea>
        	<input type="hidden" name="id_cot" value="<?php echo $cotizacion['id']; ?>">
        	<input type="hidden" name="id_cte" value="<?php echo $cotizacion['id_cliente']; ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary sendMsj"> Guardar</button>
      </div>
      <input type="hidden" name="url" value="<?php echo service('uri')->getPath() ?>">
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="saldo" tabindex="-1" aria-labelledby="saldoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="saldoLabel">Saldo a Favor:</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-6">
      			<img src="" class="saldoImg w-100">
      		</div>
      		<div class="col">
      			<div class="msjSaldo"></div>
      		</div>
      	</div>
      </div>
    </div>
  </div>
</div>
<?php if($clienteDir){ ?>
<div class="modal fade" id="datosEnvio" tabindex="-1" aria-labelledby="datosEModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="datosEModalLabel">Datos de Envío</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/cotizaciones/editar_dir" method="post" id="fm-datadir">
        	<div class="row mb-3">
        		<div class="col">
        			<label for="">Recibe:</label>
        			<input type="text" name="recibe" value="<?php echo $clienteDir['recibe'] ?>" class="form-control">
        		</div>
        	</div>
        	<div class="row mb-3">
        		<div class="col">
        			<label for="">Calle:</label>
        			<input type="text" name="calle" value="<?php echo $clienteDir['calle'] ?>" class="form-control">
        		</div>
        		<div class="col">
        			<label for="">Num Ext.:</label>
        			<input type="text" name="numExt" value="<?php echo $clienteDir['numExt'] ?>" class="form-control">
        		</div>
        		<div class="col">
        			<label for="">Num Int.:</label>
        			<input type="text" name="numInt" value="<?php echo $clienteDir['numInt'] ?>" class="form-control">
        		</div>
        	</div>
        	<div class="row mb-3">
        		<div class="col">
        			<label for="">Col.</label>
        			<input type="text" name="col" value="<?php echo $clienteDir['col'] ?>" class="form-control">
        		</div>
        		<div class="col">
        			<label for="">Del/Mun</label>
        			<input type="text" name="del_mun" value="<?php echo $clienteDir['del_mun'] ?>" class="form-control">
        		</div>
        		<div class="col">
									<label for="estado">Estado:</label>
									<select name="estado" id="estado" class="form-select" required>
										<option value="" <?php if($clienteDir['estado'] == ""){ echo "selected";} ?>>Selecciona un Estado</option>
										<option value="CDMX" <?php if($clienteDir['estado'] == "CDMX"){ echo "selected";} ?>>Ciudad de México</option>
										<option value="AGS" <?php if($clienteDir['estado'] == "AGS"){ echo "selected";} ?>>Aguascalientes</option>
										<option value="BCN" <?php if($clienteDir['estado'] == "BCN"){ echo "selected";} ?>>Baja California</option>
										<option value="BCS" <?php if($clienteDir['estado'] == "BCS"){ echo "selected";} ?>>Baja California Sur</option>
										<option value="CAM" <?php if($clienteDir['estado'] == "CAM"){ echo "selected";} ?>>Campeche</option>
										<option value="CHP" <?php if($clienteDir['estado'] == "CHP"){ echo "selected";} ?>>Chiapas</option>
										<option value="CHI" <?php if($clienteDir['estado'] == "CHI"){ echo "selected";} ?>>Chihuahua</option>
										<option value="COA" <?php if($clienteDir['estado'] == "COA"){ echo "selected";} ?>>Coahuila</option>
										<option value="COL" <?php if($clienteDir['estado'] == "COL"){ echo "selected";} ?>>Colima</option>
										<option value="DUR" <?php if($clienteDir['estado'] == "DUR"){ echo "selected";} ?>>Durango</option>
										<option value="GTO" <?php if($clienteDir['estado'] == "GTO"){ echo "selected";} ?>>Guanajuato</option>
										<option value="GRO" <?php if($clienteDir['estado'] == "GRO"){ echo "selected";} ?>>Guerrero</option>
										<option value="HGO" <?php if($clienteDir['estado'] == "HGO"){ echo "selected";} ?>>Hidalgo</option>
										<option value="JAL" <?php if($clienteDir['estado'] == "JAL"){ echo "selected";} ?>>Jalisco</option>
										<option value="EDO MEX" <?php if($clienteDir['estado'] == "EDO MEX"){ echo "selected";} ?>>Estado de M&eacute;xico</option>
										<option value="MIC" <?php if($clienteDir['estado'] == "MIC"){ echo "selected";} ?>>Michoac&aacute;n</option>
										<option value="MOR" <?php if($clienteDir['estado'] == "MOR"){ echo "selected";} ?>>Morelos</option>
										<option value="NAY" <?php if($clienteDir['estado'] == "NAY"){ echo "selected";} ?>>Nayarit</option>
										<option value="NLE" <?php if($clienteDir['estado'] == "NLE"){ echo "selected";} ?>>Nuevo Le&oacute;n</option>
										<option value="OAX" <?php if($clienteDir['estado'] == "OAX"){ echo "selected";} ?>>Oaxaca</option>
										<option value="PUE" <?php if($clienteDir['estado'] == "PUE"){ echo "selected";} ?>>Puebla</option>
										<option value="QRO" <?php if($clienteDir['estado'] == "QRO"){ echo "selected";} ?>>Quer&eacute;taro</option>
										<option value="ROO" <?php if($clienteDir['estado'] == "ROO"){ echo "selected";} ?>>Quintana Roo</option>
										<option value="SLP" <?php if($clienteDir['estado'] == "SLP"){ echo "selected";} ?>>San Luis Potos&iacute;</option>
										<option value="SIN" <?php if($clienteDir['estado'] == "SIN"){ echo "selected";} ?>>Sinaloa</option>
										<option value="SON" <?php if($clienteDir['estado'] == "SON"){ echo "selected";} ?>>Sonora</option>
										<option value="TAB" <?php if($clienteDir['estado'] == "TAB"){ echo "selected";} ?>>Tabasco</option>
										<option value="TAM" <?php if($clienteDir['estado'] == "TAM"){ echo "selected";} ?>>Tamaulipas</option>
										<option value="TLX" <?php if($clienteDir['estado'] == "TLX"){ echo "selected";} ?>>Tlaxcala</option>
										<option value="VER" <?php if($clienteDir['estado'] == "VER"){ echo "selected";} ?>>Veracruz</option>
										<option value="YUC" <?php if($clienteDir['estado'] == "YUC"){ echo "selected";} ?>>Yucat&aacute;n</option>
										<option value="ZAC" <?php if($clienteDir['estado'] == "ZAC"){ echo "selected";} ?>>Zacatecas</option>
									</select>
	        		</div>
	        		<div class="col">
	        			<label for="">C.P.:</label>
	        			<input type="text" name="cp" value="<?php echo $clienteDir['cp'] ?>" class="form-control">
	        		</div>
        	</div>
        	<input type="hidden" name="id_dir" value="<?php echo $clienteDir['id'] ?>">
        	<input type="hidden" name="id_cot" value="<?php echo $cotizacion['id']; ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary sendDataDir">Editar</button>
      </div>
    </div>
  </div>
</div>
<?php }else{ ?>
<div class="modal fade" id="datosEnvio" tabindex="-1" aria-labelledby="datosEModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="datosEModalLabel">Datos de Cliente</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<form action="/cotizaciones/editar_cte_data/<?php echo $cliente['id']; ?>" method="post">
      		<div class="row mb-3">
      			<div class="col">
      				<label for="nombre">Nombre:</label>
      				<input type="text" name="nombre" class="form-control" required value="<?php echo $cliente['nombre']; ?>">
      			</div>
      		</div>
      		<div class="row justify-content-end mb-3">
      			<div class="col-4 d-grid">
      				<button class="btn btn-primary">Guardar</button>
      			</div>
      		</div>
      		<input type="hidden" name="id_cot" value="<?php echo $cotizacion['id']; ?>">
      	</form>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<script>
	$('.sendDataDir').click(function(event) {
		$(this).addClass('d-none')
		$('#fm-datadir').submit();
	});
	$('.sendMsj').click(function(event){
		let form = $(this).closest('form');
		if($('#textoObsMsj').val() == ""){
			$('#textoObsMsj').focus()
			return false
		}else{
			form.submit();
		}
	})
	$('.sendObs').click(function(event){
		let form = $(this).closest('form');
		if($('#textoObs').val() == ""){
			$('#textoObs').focus()
			return false
		}else{
			form.submit();

		}
	})
	$('#observaciones').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) // Button that triggered the modal
		var recipient = button.data('id_cot') // Extract info from data-* attributes
		$.get('/cotizaciones/get_seguimiento/'+recipient, function(data) {
			$('.seguimiento').html(data)
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
	$('.addProd').click(function(event) {
		let form = $(this).closest('form');
		$.post('/cotizaciones/add_row', form.serialize(), function(data, textStatus, xhr) {
			$.get('/cotizaciones/getDataProds/<?php echo $cotizacion['id']; ?>', function(data) {
				$('#selectProd').val('');
				$('#cantAdd').val('')
				$('#id_prod').val('')
				$('.table-data').html(data)
			});
		});
	});
	$('.sendNum').click(function(event) {
		let form = $(this).closest('form');
		let num = $('#num').val()
		if(num == ""){
			$('#num').focus()
			return false
		}else{
			$.post('/cotizaciones/upd_num', form.serialize(), function(data, textStatus, xhr) {
				$('#num').html(data);
			});
		}
	});
	$('.upd-prods').click(function(event) {
		console.log('edit')
		$.get('/cotizaciones/upd_prods', function(data) {
			location.reload();
		});
	});
	$(function(){
		$.get('/cotizaciones/saldo/<?php echo $cotizacion['id']; ?>', function(data) {
			resp = JSON.parse(data);
			if(parseInt(resp.saldo)){
				$('.btn-saldo').removeClass('d-none')
				imagen = 'https://centrodemayoreocdmx.com.mx/saldos/' + resp.comprobante
				$('.saldoImg').attr('src', imagen);
				$('.msjSaldo').html(resp.mensaje)
			}
		});
		$.get('/cotizaciones/getDataProds/<?php echo $cotizacion['id']; ?>', function(data) {
			$('.table-data').html(data)
		});
		let productos = [
			<?php foreach (session()->get('productosAppEdit') as $prod): ?>
				{ label: "<?php echo htmlentities($prod['descripcion']); ?>", value: <?php echo $prod['id']; ?>},
			<?php endforeach ?>
		];
	    $( "#selectProd" ).autocomplete({
	        minLength: 1,
	        source: productos,
	        focus: function( event, ui ) {
	            $( "#selectProd" ).val( ui.item.label );
	                return false;
	        },
	        select: function( event, ui ) {
	          $( "#selectProd" ).val( ui.item.label );
	          $( "#id_prod" ).val( ui.item.value );
	          return false;
	        }
	    });
		$('form input').on('keydown', function(event) {
			if (event.key === 'Enter') {
				event.preventDefault();
			}
		});
		$('#copyButton').on('click', function() {
			let combinedContent = '';
            $('.copyContent').each(function() {
            	combinedContent += $(this).text() + '\n';
			});
            const tempTextarea = $('<textarea>');
			$('body').append(tempTextarea);
			tempTextarea.val(combinedContent).select();
			document.execCommand('copy');
			tempTextarea.remove();
		});



	})
</script>
</body>
</html>

