<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>


<div class="container">
    <div class="row">
        <div class="col">
            <h2 class="mt-2">Detail Komik</h2>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="/img/<?= $komik['sampul']; ?>" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"></h5><?= $komik['judul']; ?>
                            <p class="card-text"><b>Penulis : </b><?= $komik['penulis']; ?></p>
                            <p class="card-text"><b>Penerbit : </b><?= $komik['penerbit']; ?></p>
                            <a href="/Komik/edit/<?= $komik['slug']; ?>" class="btn btn-warning">Edit</a>
                            <a href="/Komik/delete/<?= $komik['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah anda yakin ?')">Delete</a>
                            <br><br>
                            <a href="/Komik/index" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>