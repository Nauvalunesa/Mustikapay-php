<?php

/**
 * MustikaPay Callback Handler
 * Mendeteksi notifikasi pembayaran sukses dari server MustikaPay.
 */

// 1. Ambil data JSON dari body request
$rawPayload = file_get_contents('php://input');
$data = json_decode($rawPayload, true);

// 2. Validasi apakah data diterima
if (!$data) {
    header('HTTP/1.1 400 Bad Request');
    exit('No data received');
}

/**
 * Berdasarkan struktur JSON MustikaPay:
 * Penanda sukses ada dua:
 * - $data['status'] == 'success' (Status pengiriman webhook)
 * - $data['data']['status'] == 'SUCCESS' (Status transaksi asli)
 */

$isSuccessOuter = isset($data['status']) && $data['status'] === 'success';
$isSuccessInner = isset($data['data']['status']) && $data['data']['status'] === 'SUCCESS';

if ($isSuccessOuter && $isSuccessInner) {
    
    // Ambil data transaksi untuk diproses
    $reference = $data['reference'];        // Ref No MustikaPay
    $orderId   = $data['order_id'];         // ID Pesanan Anda (jika ada)
    $amount    = $data['amount'];           // Nominal kotor (Gross)
    
    // Detail tambahan dari object 'data'
    $detail    = $data['data'];
    $netAmount = $detail['net_amount'];     // Nominal bersih yang masuk ke saldo
    $issuer    = $detail['issuer'];         // Contoh: DANA, OVO, SHOPEEPAY
    $service   = $data['service'];          // Contoh: QRIS, VA, EMONEY
    
    /**
     * --- LOGIKA DATABASE ANDA ---
     * 1. Cek apakah transaksi dengan $reference ini sudah pernah diproses?
     * 2. Cocokkan $amount dengan tagihan di sistem Anda.
     * 3. Jika valid, update status pesanan menjadi LUNAS.
     */
     
    // Contoh Respon Berhasil (HTTP 200)
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'OK', 'message' => 'Payment recorded successfully']);

} else {
    // Status transaksi belum sukses atau gagal
    http_response_code(200); // Tetap beri 200 agar MustikaPay tidak kirim ulang callback gagal
    header('Content-Type: application/json');
    echo json_encode(['status' => 'IGNORE', 'message' => 'Transaction is not successful']);
}
