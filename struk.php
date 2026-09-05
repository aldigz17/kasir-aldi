<?php

date_default_timezone_set('Asia/Jakarta');

require 'koneksi.php';

// Ambil data dari form
$produk_id = $_POST['produk_id'] ?? null;
$qty = (int) ($_POST['qty'] ?? 0);
$kasir = $_POST['kasir'] ?? '';
$metode = $_POST['metode_pembayaran'] ?? '';
$bayar = (int) ($_POST['bayar'] ?? 0);

// Cek data
if (!$produk_id || $qty <= 0 || !$kasir || !$metode || $bayar <= 0) {
    die("Data transaksi belum lengkap.");
}

// Ambil produk
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM produk WHERE id='$produk_id'"
);

$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    die("Produk tidak ditemukan.");
}


if ($qty > $produk['stok']) {
    die("Stok tidak mencukupi.");
}


$tanggal_transaksi = date('Y-m-d H:i:s');


$subtotal = $produk['harga'] * $qty;
$total = $subtotal;


if ($bayar < $total) {
    die("Uang pembayaran kurang.");
}


$kembalian = $bayar - $total;


$no_transaksi = "TRX" . date("YmdHis");


$query_transaksi = mysqli_query(
    $koneksi,
    "INSERT INTO transaksi
    (no_transaksi, tanggal, kasir, total, metode_pembayaran)
    VALUES
    ('$no_transaksi',
     '$tanggal_transaksi',
     '$kasir',
     '$total',
     '$metode')"
);

if (!$query_transaksi) {
    die("Gagal menyimpan transaksi: " . mysqli_error($koneksi));
}


$transaksi_id = mysqli_insert_id($koneksi);


$query_detail = mysqli_query(
    $koneksi,
    "INSERT INTO detail_transaksi
    (transaksi_id, produk_id, qty, subtotal)
    VALUES
    ('$transaksi_id',
     '$produk_id',
     '$qty',
     '$subtotal')"
);

if (!$query_detail) {
    die("Gagal menyimpan detail transaksi: " . mysqli_error($koneksi));
}


$stok_baru = $produk['stok'] - $qty;

mysqli_query(
    $koneksi,
    "UPDATE produk
     SET stok='$stok_baru'
     WHERE id='$produk_id'"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Struk Pembayaran</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }

        .struk {
            width: 400px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .alamat {
            text-align: center;
            font-size: 13px;
            color: #555;
        }

        .garis {
            border-top: 1px dashed #000;
            margin: 15px 0;
        }

        .baris {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            gap: 15px;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
        }

        .terima-kasih {
            text-align: center;
            margin-top: 20px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            border: none;
            background: #111;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .struk {
                width: 100%;
                border-radius: 0;
            }

            button {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="struk">

    <h2>DEALER MOBIL ALDI</h2>

    <div class="alamat">
        Struk Pembayaran
    </div>

    <div class="garis"></div>

    <div class="baris">
        <span>No. Transaksi</span>
        <span><?= htmlspecialchars($no_transaksi); ?></span>
    </div>

    <div class="baris">
        <span>Tanggal</span>

        <span>
            <?= date('d-m-Y', strtotime($tanggal_transaksi)); ?>
        </span>
    </div>

    <div class="baris">
        <span>Jam</span>

        <span>
            <?= date('H:i:s', strtotime($tanggal_transaksi)); ?> WIB
        </span>
    </div>

    <div class="baris">
        <span>Kasir</span>
        <span><?= htmlspecialchars($kasir); ?></span>
    </div>

    <div class="garis"></div>

    <p>
        <strong><?= htmlspecialchars($produk['nama_produk']); ?></strong>
    </p>

    <div class="baris">
        <span>
            <?= $qty; ?> ×
            Rp <?= number_format($produk['harga'], 0, ',', '.'); ?>
        </span>

        <span>
            Rp <?= number_format($subtotal, 0, ',', '.'); ?>
        </span>
    </div>

    <div class="garis"></div>

    <div class="baris total">
        <span>TOTAL</span>

        <span>
            Rp <?= number_format($total, 0, ',', '.'); ?>
        </span>
    </div>

    <div class="baris">
        <span>Bayar</span>

        <span>
            Rp <?= number_format($bayar, 0, ',', '.'); ?>
        </span>
    </div>

    <div class="baris">
        <span>Kembalian</span>

        <span>
            Rp <?= number_format($kembalian, 0, ',', '.'); ?>
        </span>
    </div>

    <div class="baris">
        <span>Metode</span>

        <span>
            <?= htmlspecialchars($metode); ?>
        </span>
    </div>

    <div class="garis"></div>

    <div class="terima-kasih">
        <p>Terima kasih telah berbelanja!</p>
        <p>Semoga harimu menyenangkan 🚗</p>
    </div>

    <button onclick="window.print()">
        🖨️ Cetak Struk
    </button>

</div>

</body>
</html>