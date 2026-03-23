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
		<?php if(check_permisos('staf')){ ?>
		<div class="row my-3">
			<div class="col-md-3">
				<div class="card mb-3">
					<div class="card-header">Total Cotizado Hoy</div>
					<div class="card-body fs-4 text-center">
						<?php echo number_format($total,2); ?>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card mb-3">
					<div class="card-header">Total Pagos Recibidos Hoy</div>
					<div class="card-body fs-4 text-center">
						<div id="totalPagos">
							
						</div>
					</div>
				</div>
			</div>
			<div class="col-md">
				<div class="card mb-3">
					<div class="card-header">Ventas del Mes</div>
					<div class="card-body">
						<canvas id="graficaPagos" width="600" height="300"></canvas>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
<?php echo view('ext/footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	$(function() {
		$.get('/reportes/totalHoy', function(data) {
			let total = parseFloat(data.total) || 0;
			let formato = total.toLocaleString('es-MX', {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			});
			$('#totalPagos').html(formato);
		});
		$.get('/reportes/pagosPorDia', function(data) {
		        const labels = data.map(p => p.dia);
		        const montos = data.map(p => parseFloat(p.total));

		        const ctx = document.getElementById('graficaPagos').getContext('2d');
		        new Chart(ctx, {
		            type: 'bar',
		            data: {
		                labels: labels,
		                datasets: [{
		                    label: 'Pagos por día (MXN)',
		                    data: montos,
		                    backgroundColor: '#4caf50'
		                }]
		            },
		            options: {
		                responsive: true,
		                scales: {
		                    y: {
		                        ticks: {
		                            callback: value => '$' + value.toLocaleString('es-MX')
		                        }
		                    }
		                }
		            }
		        });
		    });
    	});
</script>
</body>
</html>