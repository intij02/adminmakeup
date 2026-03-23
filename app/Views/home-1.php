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
<?php echo var_dump(session()->get('intended_url')) ?>
	<div class="container-fluid">
		<?php if(check_permisos('staf')){ ?>
		<div class="row my-3">
			<div class="col-md">
				<div class="row">
					<div class="col-md-3">
						<div class="card mb-3" style="border-left: solid 3px #ed6cf6">
							<div class="card-header">Total Ventas en TIENDAS por Mes</div>
							<div class="card-body fs-4 text-center">
								<?php echo number_format($ventasPosMes['ventas_mes']) ?>
							</div>
						</div>						
					</div>
					<div class="col-md-3">
						<div class="card mb-3" style="border-left: solid 3px #4caf50">
							<div class="card-header">Total Ventas en WEB por Mes</div>
							<div class="card-body fs-4 text-center">
								<?php echo number_format($ventasWebMes['ventas_mes']) ?>
							</div>
						</div>						
					</div>
					<div class="col-md-3">
						<div class="card mb-3" style="border-left: solid 3px #4caf50">
							<div class="card-header">Total Pagos Recibidos Hoy en WEB</div>
							<div class="card-body fs-4 text-center">
								<div id="totalPagos">
									
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card mb-3" style="border-left: solid 3px #4caf50">
							<div class="card-header">Total Cotizado Hoy en WEB</div>
							<div class="card-body fs-4 text-center">
								<?php echo number_format($total,2); ?>
							</div>
						</div>						
					</div>
				</div>
				<div class="row my-3">
					<div class="col">
						<div class="card">
							<div class="card-header">Productos más vendidos este año</div>
							<div class="card-body">
								<div id="loadProdVend" class="placeholder-glow">
									<div class="placeholder col-12"></div>
								</div>
								<canvas class="d-none" id="graficaMasVendidos"></canvas>
							</div>
						</div>
					</div>
				</div>
				<div class="row my-3">
					<div class="col">
						<div class="card">
							<div class="card-header">
								Ventas Web por Estado
							</div>
							<div class="card-body">
								<canvas id="chartCP"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md">
				<div class="card mb-3" style="border-left: solid 3px #4caf50">
					<div class="card-header">Ventas del Mes en WEB</div>
					<div class="card-body">
						<canvas id="graficaPagos" width="600" height="300"></canvas>
					</div>
				</div>
				<div class="card mb-3" style="border-left: solid 3px #ed6cf6">
					<div class="card-header">Ventas del Mes en TIENDAS</div>
					<div class="card-body">
						<canvas id="graficaPagosPOS" width="600" height="300"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md">
				<div class="card mb-3" style="border-left: solid 3px #2196f3">
				    <div class="card-header">Ventas acumuladas por mes (WEB vs TIENDAS)</div>
				    <div class="card-body">
				        <canvas id="graficaAnual" width="600" height="300"></canvas>
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

		$.get('/reportes/ventasPosDia', function(data) {
		        const labels = data.map(p => p.dia);
		        const montos = data.map(p => parseFloat(p.total));

		        const ctx = document.getElementById('graficaPagosPOS').getContext('2d');
		        new Chart(ctx, {
		            type: 'bar',
		            data: {
		                labels: labels,
		                datasets: [{
		                    label: 'Ventas por día (MXN)',
		                    data: montos,
		                    backgroundColor: '#ed6cf6'
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
$.get('/reportes/ventasAnuales', function(data) {
    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    const labels = data.map(d => meses[d.mes - 1]);
    const web = data.map(d => parseFloat(d.total_web));
    const pos = data.map(d => parseFloat(d.total_pos));

    const ctx = document.getElementById('graficaAnual').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Ventas WEB (MXN)',
                    data: web,
                    backgroundColor: '#4caf50'
                },
                {
                    label: 'Ventas TIENDAS (MXN)',
                    data: pos,
                    backgroundColor: '#ed6cf6'
                }
            ]
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
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    fetch("/home/apiProdXMes")
        .then(res => res.json())
        .then(productos_mes => {

            const meses = [
                "", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ];

            let labels = [];
            let values = [];
            let descriptions = [];
            document.getElementById("loadProdVend").classList.add("d-none");
            document.getElementById("graficaMasVendidos").classList.remove("d-none");

            productos_mes.forEach(p => {
                labels.push(meses[parseInt(p.mes)]);
                values.push(parseInt(p.total_vendido));
                descriptions.push(p.descripcion);
            });

            new Chart(document.getElementById("graficaMasVendidos"), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Piezas vendidas",
                        data: values
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return [
                                        "Producto: " + descriptions[context.dataIndex],
                                        "Piezas: " + values[context.dataIndex]
                                    ];
                                }
                            }
                        }
                    }
                }
            });

        })
        .catch(err => console.error("ERROR:", err));
});
fetch('/home/apiVentasPorCP')
  .then(res => res.json())
  .then(data => {
    const labels = data.map(x => x.estado);
    const values = data.map(x => x.total_pedidos);

    new Chart(document.getElementById("chartCP"), {
      type: "bar",
      data: {
        labels: labels,
        datasets: [{
          label: "Pedidos por Estado",
          data: values
        }]
      },
      options: {
        indexAxis: 'y', // ← ← Barra horizontal
      }
    });
  });

</script>
</body>
</html>