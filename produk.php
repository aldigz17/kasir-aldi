<?php
require 'koneksi.php';

// Tambah produk
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_POST['gambar'];

    mysqli_query($koneksi, "INSERT INTO produk 
        (kode_produk, nama_produk, harga, stok, gambar)
        VALUES ('$kode', '$nama', '$harga', '$stok', '$gambar')");

    header("Location: produk.php");
    exit;
}


if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM produk WHERE id='$id'");

    header("Location: produk.php");
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk Mobil</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <h1>🚗 Produk Mobil</h1>
    <p>Kelola daftar mobil</p>
</header>

<main>

    <h2>Tambah Produk</h2>

    <form method="POST">

        <input type="text" name="kode_produk" placeholder="Kode Produk" required>

        <input type="text" name="nama_produk" placeholder="Nama Mobil" required>

        <input type="number" name="harga" placeholder="Harga" required>

        <input type="number" name="stok" placeholder="Stok" required>

        <input type="text" name="gambar" placeholder="Nama file gambar, contoh: avanza.jpg">

        <button type="submit" name="tambah">Tambah Produk</button>

    </form>

    <h2>Daftar Produk</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Kode</th>
            <th>Gambar</th>
            <th>Nama Mobil</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>

        <?php while ($produk = mysqli_fetch_assoc($data)) : ?>

        <tr>
            <td><?= $produk['id']; ?></td>

            <td><?= $produk['kode_produk']; ?></td>

            <td>
                <?php if (!empty($produk['gambar'])) : ?>
                    <img 
                        src="gambar/<?= $produk['gambar']; ?>" 
                        width="120"
                    >
                <?php else : ?>
                    Tidak ada gambar
                <?php endif; ?>
            </td>

            <td><?= $produk['nama_produk']; ?></td>

            <td>
                Rp <?= number_format($produk['harga'], 0, ',', '.'); ?>
            </td>

            <td><?= $produk['stok']; ?></td>

            <td>
                <a href="?hapus=<?= $produk['id']; ?>"
                   onclick="return confirm('Hapus produk ini?')">
                    Hapus
                </a>
            </td>
        </tr>

        <?php endwhile; ?>

    </table>

</main>

</body>
</html>