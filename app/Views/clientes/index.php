<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row my-3">
    <div class="col-md">
        <div class="card shadow-sm border-light">
            <div class="card-header">Clientes</div>
            <div class="card-body">
                <div id="clientesAlert" class="alert d-none" role="alert"></div>
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" id="reloadClientesBtn" class="btn btn-outline-dark btn-sm">Actualizar</button>
                </div>
                <div class="table-responsive">
                    <table id="clientesTable" class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Pedidos Pagados</th>
                                <th>Pedidos No Pagados</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPedidos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered align-middle mb-0">
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url('js/clientes.js') ?>"></script>
<?= $this->endSection() ?>

