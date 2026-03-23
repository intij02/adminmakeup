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
									<input type="text" class="form-control" name="nombre" required>
								</div>
								<div class="col-md">
									<label for="user">Usuario:</label>
									<input type="text" class="form-control" name="user" required>
								</div>
								<div class="col-md">
									<label for="pass">Contraseña</label>
									<input type="text" class="form-control" name="pass" required>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col">
									<label for="permisos" class="d-block">Permisos para este usuario:</label>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="admin" value="/">
									  <label class="form-check-label text-warning" for="admin">Admin</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="super" value="super" >
									  <label class="form-check-label text-warning" for="super">Supervisor</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="nuevo_user" value="admin/nuevo_usuario">
									  <label class="form-check-label" for="nuevo_user">Nuevo Usuario</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="users" value="admin/usuarios">
									  <label class="form-check-label" for="users">Usuarios</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="edit_users" value="admin/editar_usuario/">
									  <label class="form-check-label" for="edit_users">Editar Usuarios</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="reportes" value="reportes/fecha,reportes">
									  <label class="form-check-label" for="reportes">Reportes</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="productos" value="productos">
									  <label class="form-check-label" for="productos">Productos</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="productos/editar_producto" value="productos/editar_producto">
									  <label class="form-check-label" for="productos/editar_producto">Editar Productos</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="clientes" value="clientes">
									  <label class="form-check-label" for="clientes">Clientes</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="pagos" value="pagos/fecha,pagos" >
									  <label class="form-check-label" for="pagos">Pagos</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="cotizaciones" value="cotizaciones/fecha,cotizaciones">
									  <label class="form-check-label" for="cotizaciones">Cotizaciones</label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input checkbox" type="checkbox"  id="pagos_tienda" value="pagos/fecha_tienda,pagos/tienda">
									  <label class="form-check-label" for="pagos_tienda">Pagos Tienda</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<button type="submit" class="btn btn-primary send">Guardar</button>
								</div>
							</div>
							<input type="hidden" name="permisos" id="permisos">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>
<script>
let permisos = [];
    $('.checkbox').click(function(event) {
        let valor = $(this).val();
        let index = permisos.indexOf(valor);

        if (index !== -1) {
            permisos.splice(index, 1);
        } else {
            permisos.push(valor);
        }

        $('#permisos').val(permisos.join(','));
    });</script>
</body>
</html>