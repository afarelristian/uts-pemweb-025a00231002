<?php

declare(strict_types=1);

function e(string|int $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function formatRupiah(int $harga): string
{
    return 'Rp' . number_format($harga, 0, ',', '.');
}

function labelPedas(array $menu): string
{
    if ($menu['kategori'] === 'Mi Level') {
        return 'Level ' . (int) $menu['level'];
    }

    return $menu['pedas'] === 'Pedas' ? 'Pedas' : 'Tidak Pedas';
}

function daftarKategori(array $menu): array
{
    return array_values(array_unique(array_column($menu, 'kategori')));
}

function saringMenu(array $menu, string $kategori): array
{
    if ($kategori === '') {
        return $menu;
    }

    return array_filter(
        $menu,
        static fn(array $item): bool => $item['kategori'] === $kategori
    );
}

function hitungTersedia(array $menu): int
{
    return count(
        array_filter($menu, static fn(array $item): bool => $item['tersedia'])
    );
}

function ringkasanMenu(array $menu): array
{
    return [
        'Total Menu' => count($menu),
        'Menu Tersedia' => hitungTersedia($menu),
        'Kategori' => count(daftarKategori($menu)),
    ];
}

function hargaTermurahPerKategori(array $menu): array
{
    $hasil = [];

    foreach (daftarKategori($menu) as $kategori) {
        $harga = array_column(
            array_filter(
                $menu,
                static fn(array $item): bool => $item['kategori'] === $kategori
                    && $item['tersedia']
            ),
            'harga'
        );

        if ($harga !== []) {
            $hasil[$kategori] = min($harga);
        }
    }

    return $hasil;
}

function labelHargaTermurah(array $menu): string
{
    $label = [];

    foreach (hargaTermurahPerKategori($menu) as $kategori => $harga) {
        $label[] = $kategori . ' ' . formatRupiah($harga);
    }

    return implode(' · ', $label);
}

function urlKategori(string $kategori): string
{
    if ($kategori === '') {
        return 'katalog.php';
    }

    return 'katalog.php?kategori=' . urlencode($kategori);
}