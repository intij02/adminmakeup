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
						Usuarios
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Nombre</th>
										<th>Usuario</th>
										<th>Opciones</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($users as $user): if($user['id'] != 1){ ?>
									<tr>
										<td><?php echo $user['nombre']; ?></td>
										<td><?php echo $user['user']; ?></td>
										<td>
											<?php if(check_permisos('admin')){ ?>
												<a href="/admin/editar_usuario/<?php echo $user['id']; ?>" class="btn btn-secondary">Editar</a>
											<?php } ?>
										</td>
									</tr>
									<?php } endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>

</body>
</html>