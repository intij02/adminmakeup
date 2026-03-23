<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h1 class="h4 mb-0">Administración de Usuarios</h1>
                    <button class="btn btn-dark" id="newUserBtn" type="button">Nuevo usuario</button>
                </div>
                <div id="usersAlert" class="alert d-none" role="alert"></div>
                <div class="table-responsive">
                    <table class="table align-middle" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Permisos</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="userModalTitle">Nuevo usuario</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" id="userId" name="id" value="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="nombre">Nombre</label>
                            <input id="nombre" class="form-control" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="user">Usuario</label>
                            <input id="user" class="form-control" name="user" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pass">Contraseña</label>
                            <input id="pass" class="form-control" name="pass" type="password" autocomplete="new-password">
                            <div class="form-text">En edición, déjala vacía para conservar la actual.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Permisos</label>
                            <div class="row g-2">
                                <?php foreach (['admin','reportes','productos','clientes','pagos','cotizaciones'] as $perm): ?>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox" type="checkbox" value="<?= esc($perm) ?>" id="perm_<?= esc($perm) ?>">
                                        <label class="form-check-label" for="perm_<?= esc($perm) ?>"><?= esc(ucfirst($perm)) ?></label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                <button class="btn btn-dark" id="saveUserBtn" type="button">Guardar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url('js/admin-users.js') ?>"></script>
<?= $this->endSection() ?>

