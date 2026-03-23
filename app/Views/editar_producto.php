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
		<?php if(isset($_GET['save'])){ ?>
		<div class="row my-2">
			<div class="col">
				<div class="alert alert-success text-center p-2">
					<i class="fa-solid fa-thumbs-up fa-xl"></i> Cambios Guardados!
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="row my-3">
			<div class="col-md">
				<div class="card shadow-sm border-light">
					<div class="card-header">
						Editar Producto
					</div>
					<div class="card-body">
						<form action="<?php echo current_url() ?>" method="post">
							<div class="row mb-3">
								<div class="col">
									<label for="">Descripción:</label>
									<input type="text" name="desc" class="form-control" value="<?php echo $producto['descripcion'] ?>">
								</div>
							</div>
							<div class="row my-1">
								<div class="col">
									<label for="">Precios:</label>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-md">
									<label for="">Precio Público:</label>
									<input type="tel" class="form-control" name="precio_p" value="<?php echo $producto['precio_p'] ?>">
								</div>
								<div class="col-md">
									<label for="">Precio 1:</label>
									<input type="tel" class="form-control" name="precio_1" value="<?php echo $producto['precio_1'] ?>">
								</div>
								<div class="col-md">
									<label for="">Precio 2:</label>
									<input type="tel" class="form-control" name="precio_2" value="<?php echo $producto['precio_2'] ?>">
								</div>
								<div class="col-md">
									<label for="">Precio 3:</label>
									<input type="tel" class="form-control" name="precio_3" value="<?php echo $producto['precio_3'] ?>">
								</div>
								<div class="col-md">
									<label for="">Precio 4:</label>
									<input type="tel" class="form-control" name="precio_4" value="<?php echo $producto['precio_4'] ?>">
								</div>
								<div class="col-md">
									<label for="">Precio 5:</label>
									<input type="tel" class="form-control" name="precio_5" value="<?php echo $producto['precio_5'] ?>">
								</div>
							</div>
							<div class="row mb-3">
								<div class="col">
									<label for="">Peso:</label>
									<input type="number" name="peso" value="<?php echo $producto['peso']; ?>" class="form-control">
								</div>
								<div class="col">
									<label for="">Existencia:</label>
									<input type="tel" name="existencia" value="<?php echo $producto['existencia']; ?>" class="form-control">
								</div>
								<div class="col">
									<label for="">Limite:</label>
									<input type="tel" name="limite_num" value="<?php echo $producto['limite_num'] ?>" class="form-control">
								</div>
								<div class="col">
									<label for="">Minimo:</label>
									<input type="tel" name="minimo" value="<?php echo $producto['minimo'] ?>" class="form-control">
								</div>
								<div class="col">
									<label for="">Peso:</label>
									<input type="tel" name="peso" value="<?php echo $producto['peso'] ?>" class="form-control">
								</div>
							</div>
							<div class="row justify-content-end">
								<div class="col-3">
									<div class="d-grid gap-2">
										<button class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>
<script>
	$(document).ready(function() {        
		setTimeout(function() {
			$(".alert").hide();
		}, 3000);
	});
</script>
</body>
</html>