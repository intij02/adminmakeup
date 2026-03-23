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
						Pagos
					</div>
					<div class="card-body">
						<?php echo service('uri')->getPath() ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>

</body>
</html>