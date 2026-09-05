<?php

$koneksi = mysqli_connect("localhost", "root", "", "transaksi");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}