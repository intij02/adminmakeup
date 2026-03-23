<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ERP Centro de Mayoreo</title>
<?php echo view('ext/links'); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<body>
<?php echo view('ext/header'); ?>

	<div class="container">
		<div class="row my-3">
			<div class="col-md">
				<div class="card shadow-sm border-light">
					<div class="card-header">
						Clientes
					</div>
					<div class="card-body">
						<div class="table-responsive">
					    <table id="clientesTable" class="table table-striped">
					        <thead>
					            <tr>
					                <th>Nombre</th>
					                <th>Teléfono</th>
					                <th>Pedidos Pagados</th>
					                <th>Pedidos No Pagados</th>
					            </tr>
					        </thead>
					    </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo view('ext/footer'); ?>

<div class="modal fade" id="modalPedidos" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalTitulo"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <table class="table table-sm table-bordered">
          <thead>
            <tr>
              <th>Pedido</th>
              <th>Fecha</th>
              <th>Total</th>
              <th class="text-center">Pagado</th>
            </tr>
          </thead>
          <tbody id="tablaPedidos"></tbody>
        </table>
      </div>

    </div>
  </div>
</div>



<script>

	function formatearFecha(fecha) {
    const f = new Date(fecha + 'T00:00:00'); // evita desfase
    const dia = String(f.getDate()).padStart(2, '0');
    const mes = String(f.getMonth() + 1).padStart(2, '0');
    const anio = f.getFullYear();

    return `${dia}-${mes}-${anio}`;
}

function formatearPrecio(num) {
    return Number(num).toLocaleString('es-MX', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

$(document).on('click', '.ver-pedidos', function (e) {
    e.preventDefault();

    let id = $(this).data('id');
    let telefono = $(this).data('telefono');

    $('#modalTitulo').text('Pedidos de ' + telefono);
    $('#tablaPedidos').html('<tr><td colspan="4">Cargando...</td></tr>');

    $('#modalPedidos').modal('show');

    $.get('<?= base_url("clientes/pedidos") ?>/' + id, function (res) {

        let html = '';

        if (res.length === 0) {
            html = '<tr><td colspan="4">Sin pedidos</td></tr>';
        } else {
            res.forEach(p => {
                html += `
                    <tr>
                        <td>#${p.id}</td>
                        <td>${formatearFecha(p.fecha)}</td>
                        <td>$${formatearPrecio(p.total)}</td>
                        <td class="text-center">
                            ${parseInt(p.pagado) === 1
        ? '<i class="bi bi-check-circle-fill text-success"></i>'
        : '<i class="bi bi-x-circle-fill text-danger"></i>'}
                        </td>
                    </tr>
                `;
            });
        }

        $('#tablaPedidos').html(html);
    });
});


	$(function(){
    $('#clientesTable').DataTable({
        ajax: '<?= base_url("clientes/datatable") ?>',
		columns: [
		    { data: 'nombre' },
		    {
		        data: 'telefono',
		        render: function (data, type, row) {
		            return `
		                <a href="#" 
		                   class="ver-pedidos" 
		                   data-id="${row.id}"
		                   data-telefono="${row.telefono}">
		                   ${data}
		                </a>
		            `;
		        }
		    },
		    { data: 'pedidos_pagados' },
		    { data: 'pedidos_no_pagados' }
		],
        pageLength: 100,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
        }
    });
	})
</script>
</body>
</html>