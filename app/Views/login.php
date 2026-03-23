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
		<div class="row justify-content-center my-5">
			<div class="col-md-5">
				<div class="card shadow border-light my-2">
					<div class="card-header text-bg-dark text-center fs-4 p-2 fw-bold">
						ERP Centro de Mayoreo <?php echo session('intended_url') ?>
					</div>
					<div class="card-body">
						<p class="text-body-tertiary text-center p-3">Introduce tu usuaro y contraseña</p>
						<form method="post" action="<?php echo current_url(); ?>">
							<div class="row mb-3">
								<div class="col">
									<label for="user">Usuario:</label>
									<input type="text" name="user" required class="form-control form-control-lg" autofocus>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col">
									<label for="pass">Contraseña:</label>
									<input type="password" class="form-control form-control-lg" name="pass" placeholder="....">
								</div>
							</div>
							<div class="row justify-content-end">
								<div class="col-4 d-grid gap-2">
									<button class="btn btn-dark">Entrar</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>

</body>
</html>