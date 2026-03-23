<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Restablecer contraseña</title>
<?php echo view('ext/links'); ?>

</head>
<body>
	<div class="container">
		<?php if(isset($_GET['success'])){ ?>
			<div class="row my-2">
				<div class="col">
					<div class="alert alert-success text-center fs-3">
						Exito!
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="row justify-content-center my-4">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<form action="/clientes/resetPassDo" method="post">
						<div class="row my-2">
							<dv class="col-md"><input type="tel" name="tel" placeholder="Teléfono" autocomplete="off" class="form-control" autofocus></dv>
						</div>
						<div class="row my-2">
							<div class="col d-grid">
								<button class="btn btn-primary">Restablecer</button>
							</div>
						</div>
						</form>						
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>