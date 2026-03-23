<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center my-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-light my-2">
            <div class="card-header text-bg-dark text-center fs-4 p-2 fw-bold">ERP Centro de Mayoreo</div>
            <div class="card-body p-4">
                <p class="text-body-tertiary text-center mb-4">Introduce tu usuario y contraseña</p>
                <div id="loginAlert" class="alert <?= session('error') ? 'alert-danger' : 'd-none' ?>" role="alert">
                    <?= esc((string) session('error')) ?>
                </div>
                <form id="loginForm" method="post" action="/control" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="user" class="form-label">Usuario</label>
                        <input id="user" type="text" name="user" required class="form-control form-control-lg" autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="pass" class="form-label">Contraseña</label>
                        <input id="pass" type="password" name="pass" required class="form-control form-control-lg" autocomplete="current-password">
                    </div>
                    <div class="d-grid mt-4">
                        <button class="btn btn-dark btn-lg" type="submit">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/auth.js') ?>"></script>
<?= $this->endSection() ?>
