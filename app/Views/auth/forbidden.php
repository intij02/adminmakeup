<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center my-5">
    <div class="col-md-8 col-lg-6 text-center">
        <h1 class="h3">No tienes permisos para este contenido</h1>
        <a href="/" class="btn btn-dark mt-3">Regresar</a>
    </div>
</div>
<?= $this->endSection() ?>
