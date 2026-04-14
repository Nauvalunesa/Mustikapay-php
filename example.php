<?php

/**
 * Pastikan Anda sudah menjalankan 'composer install'
 * atau sudah menyertakan autoloader composer.
 */
require_once __DIR__ . '/vendor/autoload.php';

use MustikaPay\MustikaPay;
use MustikaPay\Exceptions\MustikaPayException;

$apiKey = "MP-xxxx-xxxx"; // Ganti dengan API Key Anda
$mp = new MustikaPay($apiKey);

try {
    // 1. Cek Saldo
    echo "--- Cek Saldo ---\n";
    $saldo = $mp->getBalance("username_anda");
    echo "Saldo: " . $saldo['saldo'] . "\n\n";

    // 2. Membuat QRIS
    echo "--- Membuat QRIS ---\n";
    $qris = $mp->createQRIS(10000);
    echo "Ref No: " . $qris['ref_no'] . "\n";
    echo "QR Content: " . $qris['qr_content'] . "\n\n";

    // 3. Membuat Virtual Account (VA)
    echo "--- Membuat VA (BCA) ---\n";
    $va = $mp->createVA(25000, "BCA", "Nama Pelanggan");
    echo "VA Number: " . $va['va_number'] . "\n";
    echo "Bank: " . $va['bank_code'] . "\n\n";

    // 4. Membuat E-Money (DANA)
    echo "--- Membuat E-Money (PAYDANA) ---\n";
    $emoney = $mp->createEmoney(50000, "08123456789", "PAYDANA");
    echo "Payment Link: " . $emoney['emoneyData']['additionalInfo']['urlPayment'] . "\n\n";

    // 5. Membuat Retail (Alfamart)
    echo "--- Membuat Retail (Alfamart) ---\n";
    $retail = $mp->createRetail(100000, "ALFAMART", "Nama Pelanggan");
    echo "Payment Code: " . $retail['payment_code'] . "\n\n";

    // 6. Membuat SNAP Transaction
    echo "--- Membuat SNAP Checkout ---\n";
    $snap = $mp->createSnapTransaction(150000, "ORDER-123");
    echo "Token: " . $snap['token'] . "\n";
    echo "Redirect URL: " . $snap['redirect_url'] . "\n\n";

} catch (MustikaPayException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage() . "\n";
}
