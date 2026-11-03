<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

$menu = require __DIR__ . '/data.php';
$daftarKategori = daftarKategori($menu);
$filter = filter_input(INPUT_GET, 'kategori');

if (!is_string($filter) || !in_array($filter, $daftarKategori, true)) {
    $filter = '';
}

$menuTampil = saringMenu($menu, $filter);
$ringkasan = ringkasanMenu($menu);

$tabFilter = ['' => 'Semua'];

foreach ($daftarKategori as $kategori) {
    $tabFilter[$kategori] = $kategori;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Menu | Hahu Jebredd</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="judul">
    <a class="usaha" href="index.php">Hahu <span>Jebredd.</span></a>
    <nav aria-label="Navigasi utama">
        <a href="index.php">Beranda</a>
        <a class="aktif" href="katalog.php">Katalog Menu</a>
    </nav>
</header>

<main class="container page-content">
    <section class="page-heading">
        <p class="sub-judul">MENU KAMI</p>
        <h1>Katalog <span>Hahu Jebredd</span></h1>
        <p>Temukan tahu isi favorit dan mi pedas pilihanmu.</p>
        <p class="muted">Seluruh harga di halaman ini adalah harga simulasi.</p>
    </section>

    <section class="stats" aria-label="Ringkasan menu">
        <?php foreach ($ringkasan as $label => $angka): ?>
            <div>
                <strong><?= e($angka) ?></strong>
                <span><?= e($label) ?></span>
            </div>
        <?php endforeach; ?>
    </section>
    <p class="muted">Harga termurah: <?= e(labelHargaTermurah($menu)) ?></p>

    <section class="section">
        <div class="section-title-row">
            <h2>Pilihan Menu</h2>
            <nav class="filter-tabs" aria-label="Filter kategori">
                <?php foreach ($tabFilter as $nilai => $label): ?>
                    <a
                        class="<?= e($filter === $nilai ? 'on' : '') ?>"
                        href="<?= e(urlKategori($nilai)) ?>"
                    ><?= e($label) ?></a>
                <?php endforeach; ?>
            </nav>
        </div>

        <div class="menu-grid">
            <?php foreach ($menuTampil as $item): ?>
                <article class="menu-card">
                    <img
                        class="menu-image"
                        src="<?= e($item['kategori'] === 'Tahu Isi' ? 'assets/tahu-isi-menu.jpeg' : 'assets/mie-level.jpeg') ?>"
                        alt="Ilustrasi <?= e($item['nama']) ?>"
                        loading="lazy"
                    >
                    <div class="menu-card-top">
                        <span class="tag"><?= e($item['kategori']) ?></span>
                        <?php if ($item['tersedia']): ?>
                            <span class="tag ada">Tersedia</span>
                        <?php else: ?>
                            <span class="tag takada">Habis</span>
                        <?php endif; ?>
                    </div>
                    <h3><?= e($item['nama']) ?></h3>
                    <p class="pedas-label"><?= e(labelPedas($item)) ?></p>
                    <p class="harga"><?= e(formatRupiah($item['harga'])) ?></p>
                </article>
            <?php endforeach; ?>

            <?php if ($menuTampil === []): ?>
                <p>Belum ada menu pada kategori ini.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer class="footer">
    <p>&copy; <?= e(date('Y')) ?> Hahu Jebredd. Jangan ngaku pedas kalau belum coba!</p>
    <p class="muted">WA : 082241100309 | Instagram : @afrll.r</p>
</footer>
</body>
</html>
