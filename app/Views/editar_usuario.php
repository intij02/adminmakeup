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
						Nuevo Usuario:
					</div>
					<div class="card-body">
						<form method="post">
							<div class="row mb-3">
								<div class="col-md">
									<label for="nombre">Nombre:</label>
									<input type="text" class="form-control" name="nombre" required value="<?php echo $user['nombre']; ?>">
								</div>
								<div class="col-md">
									<label for="user">Usuario:</label>
									<input type="text" class="form-control" name="user" required value="<?php echo $user['user']; ?>">
								</div>
								<div class="col-md">
									<label for="pass" class="d-block">Contraseña:</label>
									<button class="btn btn-secondary" type="button" id="pass">Cambiar</button>
									<input type="text" name="pass" class="form-control d-none pass">
									<input type="hidden" class="form-control" name="pass_old" value="<?php echo $user['pass']; ?>">
								</div>
							</div>
							<?php $urls = explode(",", $permisos['permisos']); ?>
							<div class="row mb-3">
								<div class="col">
									<label for="permisos">Permisos para este usuario:</label>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="admin" value="admin" <?php if(in_array('admin', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label text-warning" for="admin">Admin</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="super" value="super" <?php if(in_array('super', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label text-warning" for="super">Supervisor</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="nuevo_user" value="admin/nuevo_usuario" <?php if(in_array('admin/nuevo_usuario', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label" for="nuevo_user">Nuevo Usuario</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="users" value="admin/usuarios" <?php if(in_array('admin/usuarios', $urls)){ echo "checked"; } ?>> 
									  <label class="form-check-label" for="users">Usuarios</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="edit_users" value="admin/editar_usuario" <?php if(in_array('admin/editar_usuario', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label" for="edit_users">Editar Usuarios</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="reportes" value="reportes/fecha,reportes" <?php if(in_array('reportes', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label" for="reportes">Reportes</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="productos" value="productos" <?php if(in_array('productos', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label" for="productos">Productos</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="productos/editar_producto" value="productos/editar_producto" <?php if(in_array('productos/editar_producto', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label" for="productos/editar_producto">Editar Productos</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="clientes" value="clientes" <?php if(in_array('clientes', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label" for="clientes">Clientes</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="pagos" value="pagos/fecha,pagos" <?php if(in_array('pagos', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label" for="pagos">Pagos</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="cotizaciones" value="cotizaciones/fecha,cotizaciones" <?php if(in_array('cotizaciones', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label" for="cotizaciones">Cotizaciones</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="pagos_tienda" value="pagos/fecha_tienda,pagos/tienda" <?php if(in_array('pagos/tienda', $urls)){ echo "checked"; } ?>>
									  <label class="form-check-label" for="pagos_tienda">Pagos Tienda</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<button type="submit" class="btn btn-primary send">Guardar</button>
								</div>
							</div>
							<input type="hidden" name="permisos" id="permisos" value="<?php echo $permisos['permisos']; ?>">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>
<script>
	$('#pass').click(function(event) {
		$('.pass').removeClass('d-none');
		$(this).addClass('d-none')
		$('.pass').focus();
	});
    $('.checkbox').click(function(event) {
		let permisos = [];
        $('#permisos').val('');
        $('.checkbox').each(function(index, val) {
        	 if($(this).is(':checked')){
        	 	permisos.push($(this).val());
        	 }
        });
        $('#permisos').val(permisos.join(','));
    });</script>
</body>
</html>