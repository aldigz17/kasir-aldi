<?php
require 'koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Produk tidak ditemukan.");
}

$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id='$id'");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    die("Produk tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kasir - Detail Pembelian</title>

    <link rel="stylesheet" href="css/style.css">

    <style>
        .kasir-box {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .gambar-kasir {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .total-box {
            background: #f3f3f3;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .total-box p {
            margin: 5px 0;
        }

        .kembalian-box {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .kembalian-box label {
            font-weight: bold;
            display: block;
            margin-bottom: 7px;
        }

        .kembalian-box input {
            width: 100%;
            padding: 12px;
            border: 1px solid #aaa;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            background: white;
        }

        .btn-bayar {
            width: 100%;
            padding: 14px;
            background: #111;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-bayar:hover {
            background: #333;
        }
    </style>
</head>

<body>

<header>
    <h1>🛒 Kasir</h1>
    <p>Detail Pembelian</p>
</header>

<main>

    <div class="kasir-box">

        <h2>Detail Pembelian</h2>

        <br>

        <?php if (!empty($produk['gambar'])) : ?>

            <img 
                src="gambar/<?= htmlspecialchars($produk['gambar']); ?>" 
                alt="<?= htmlspecialchars($produk['nama_produk']); ?>"
                class="gambar-kasir"
            >

        <?php else : ?>

            <div class="no-gambar">🚗</div>

        <?php endif; ?>


        

        <h2><?= htmlspecialchars($produk['nama_produk']); ?></h2>

        <p>
            Harga:
            <strong>
                Rp <?= number_format($produk['harga'], 0, ',', '.'); ?>
            </strong>
        </p>

        <p>
            Stok:
            <?= $produk['stok']; ?>
        </p>


        <br>


        

        <form action="struk.php" method="POST">

            <input 
                type="hidden" 
                name="produk_id" 
                value="<?= $produk['id']; ?>"
            >


          

            <div class="form-group">

                <label>Jumlah:</label>

                <input 
                    type="number"
                    name="qty"
                    id="qty"
                    value="1"
                    min="1"
                    max="<?= $produk['stok']; ?>"
                    required
                >

            </div>


            

            <div class="total-box">

                <p>
                    Harga Satuan:
                    <strong id="harga">
                        Rp <?= number_format($produk['harga'], 0, ',', '.'); ?>
                    </strong>
                </p>

                <p>
                    Total:
                    <strong id="total">
                        Rp <?= number_format($produk['harga'], 0, ',', '.'); ?>
                    </strong>
                </p>

            </div>


            

            <div class="form-group">

                <label>Nama Kasir:</label>

                <input 
                    type="text"
                    name="kasir"
                    placeholder="Masukkan nama kasir"
                    required
                >

            </div>


            

            <div class="form-group">

                <label>Metode Pembayaran:</label>

                <select 
                    name="metode_pembayaran"
                    required
                >

                    <option value="Cash">Cash</option>
                    <option value="Transfer">Transfer</option>
                    <option value="QRIS">QRIS</option>

                </select>

            </div>


            

            <div class="form-group">

                <label>Uang Bayar:</label>

                <input 
                    type="number"
                    name="bayar"
                    id="bayar"
                    placeholder="Masukkan uang pembayaran"
                    min="0"
                    required
                >

            </div>


            

            <div class="kembalian-box">

                <label>Uang Kembalian:</label>

                <input 
                    type="text"
                    id="kembalian"
                    value="Rp 0"
                    readonly
                >

            </div>



            <button 
                type="submit"
                class="btn-bayar"
            >
                💳 Bayar & Cetak Struk
            </button>

        </form>

    </div>

</main>


<script>
    const hargaProduk = <?= $produk['harga']; ?>;

    const qtyInput = document.getElementById('qty');
    const bayarInput = document.getElementById('bayar');

    const totalText = document.getElementById('total');
    const kembalianInput = document.getElementById('kembalian');

    function hitungTotal() {

        const qty = parseInt(qtyInput.value) || 0;
        const bayar = parseInt(bayarInput.value) || 0;

       
        const total = hargaProduk * qty;

        
        totalText.innerText =
            'Rp ' + total.toLocaleString('id-ID');

        
        const kembalian = bayar - total;

        if (bayar === 0) {

            kembalianInput.value = 'Rp 0';

        } else if (kembalian >= 0) {

            kembalianInput.value =
                'Rp ' + kembalian.toLocaleString('id-ID');

        } else {

            kembalianInput.value =
                'Uang kurang Rp ' +
                Math.abs(kembalian).toLocaleString('id-ID');
        }
    }

    
    qtyInput.addEventListener('input', hitungTotal);
    qtyInput.addEventListener('change', hitungTotal);

    
    bayarInput.addEventListener('input', hitungTotal);
    bayarInput.addEventListener('change', hitungTotal);

    // Jalankan pertama kali
    hitungTotal();
</script>

</body>
</html>