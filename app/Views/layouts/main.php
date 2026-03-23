<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-header" content="<?= esc(csrf_header()) ?>">
    <meta name="csrf-token-name" content="<?= esc(csrf_token()) ?>">
    <meta name="csrf-token-value" content="<?= esc(csrf_hash()) ?>">
    <title><?= esc($title ?? 'ERP Centro de Mayoreo') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
    <?= $this->renderSection('styles') ?>
</head>
<body>
<?php $user = session('user'); ?>
<?php if (is_array($user)): ?>
<header class="legacy-header shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-dark py-2">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/">ERP Centro de Mayoreo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if (check_permisos('reportes')): ?>
                        <li class="nav-item"><a class="nav-link" href="#">Reportes</a></li>
                    <?php endif; ?>
                    <?php if (check_permisos('productos')): ?>
                        <li class="nav-item"><a class="nav-link" href="#">Productos</a></li>
                    <?php endif; ?>
                    <?php if (check_permisos('clientes')): ?>
                        <li class="nav-item"><a class="nav-link" href="/clientes">Clientes</a></li>
                    <?php endif; ?>
                    <?php if (check_permisos('pagos')): ?>
                        <li class="nav-item"><a class="nav-link" href="#">Pagos</a></li>
                    <?php endif; ?>
                    <?php if (check_permisos('cotizaciones')): ?>
                        <li class="nav-item"><a class="nav-link" href="#">Cotizaciones</a></li>
                    <?php endif; ?>
                    <?php if (check_permisos('admin')): ?>
                        <li class="nav-item"><a class="nav-link" href="/admin/usuarios">Usuarios</a></li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center gap-3 text-white">
                    <span class="small fw-semibold"><?= esc($user['nombre'] ?? '') ?></span>
                    <a class="btn btn-sm btn-outline-light" href="/control/exit">Salir</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<?php endif; ?>

<main class="container py-4">
    <?= $this->renderSection('content') ?>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
