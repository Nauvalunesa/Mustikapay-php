# MustikaPay PHP SDK

SDK Resmi PHP untuk integrasi API Pembayaran MustikaPay. Mendukung QRIS Dinamis, Virtual Account (VA), E-Money, dan Retail (Alfamart/Indomaret).

## Instalasi

Gunakan Composer untuk menginstall SDK ini:

```bash
composer require mustikapay/mustikapay-php
```

## Cara Penggunaan

### 1. Inisialisasi
```php
require 'vendor/autoload.php';

use MustikaPay\MustikaPay;

$apiKey = "MP-xxxx-xxxx"; // API Key dari Dashboard
$mp = new MustikaPay($apiKey);
```

### 2. Membuat QRIS
```php
$qris = $mp->createQRIS(10000);
echo "Bayar di: " . $qris['qr_content'];
```

### 3. Membuat Virtual Account (VA)
Gunakan kode bank dalam format angka (misal: BCA = 014).
```php
use MustikaPay\Constants\BankCode;

$va = $mp->createVA(25000, BankCode::BCA, "Nama Pelanggan");
echo "Nomor VA: " . $va['va_number'];
```

### 4. Membuat E-Money (Direct Pay)
```php
use MustikaPay\Constants\EmoneyCode;

$em = $mp->createEmoney(50000, "08123456789", EmoneyCode::DANA);
echo "Klik untuk bayar: " . $em['emoneyData']['additionalInfo']['urlPayment'];
```

### 5. Webhook (Callback)
Buat file `callback.php` di server Anda untuk menerima notifikasi otomatis saat pelanggan membayar.

```php
$rawPayload = file_get_contents('php://input');
$data = json_decode($rawPayload, true);

if ($data['status'] === 'success' && $data['data']['status'] === 'SUCCESS') {
    // Proses pesanan di database Anda
    $ref = $data['reference'];
    $amount = $data['amount'];
    
    http_response_code(200);
    echo json_encode(['status' => 'OK']);
}
```

## Lisensi
MIT
