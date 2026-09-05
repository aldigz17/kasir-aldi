<?php
require 'koneksi.php';

$query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kasir Mobil</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div>
            <h1>Kasir Mobil</h1>
            <p>Dealer Mobil Aldi</p>
        </div>
    </header>


    <!-- KONTEN -->
    <main>

        <div class="judul-halaman">
            <div>
                <h2>Daftar Mobil</h2>
                <p>Pilih mobil yang ingin diproses</p>
            </div>
        </div>


        <!-- DAFTAR MOBIL -->
        <div class="produk-container">

            <?php while ($produk = mysqli_fetch_assoc($query)) : ?>

                <div class="produk-card">

                    <!-- GAMBAR -->
                    <div class="gambar-produk">

                        <?php if (!empty($produk['gambar'])) : ?>

                            <img 
                                src="gambar/<?= htmlspecialchars($produk['gambar']); ?>"
                                alt="<?= htmlspecialchars($produk['nama_produk']); ?>"
                            >

                        <?php else : ?>

                            <div class="no-gambar">
                                🚗
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- INFORMASI -->
                    <div class="produk-info">

                        <span class="kode-produk">
                            <?= htmlspecialchars($produk['kode_produk']); ?>
                        </span>

                        <h3>
                            <?= htmlspecialchars($produk['nama_produk']); ?>
                        </h3>

                        <p class="harga">
                            Rp <?= number_format($produk['harga'], 0, ',', '.'); ?>
                        </p>

                        <p class="stok">
                            Stok tersedia: <?= $produk['stok']; ?>
                        </p>


                        <!-- TOMBOL -->
                        <a href="transaksi.php?id=<?= $produk['id']; ?>">
                            <button class="btn-transaksi">
                                Tambah ke Transaksi
                            </button>
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    </main>

</body>

</html>