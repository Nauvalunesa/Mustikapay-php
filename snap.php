<?php

/**
 * MustikaPay SNAP UI Helper
 * File ini membantu menampilkan modal checkout MustikaPay (SNAP Window).
 * Jika metode adalah PAYDANA, maka akan langsung diarahkan (Full Redirect).
 */

$token  = isset($_GET['token']) ? $_GET['token'] : '';
$method = isset($_GET['method']) ? strtoupper($_GET['method']) : '';

if (empty($token)) {
    die("Error: Token SNAP tidak ditemukan.");
}

// KHUSUS PAYDANA: Jangan di dalam window (modal), harus Full Redirect
if ($method === 'PAYDANA' || $method === 'DANA') {
    header("Location: https://mustikapayment.com/snap/v1/pay/" . $token . "?method=" . $method);
    exit;
}

// UNTUK METODE LAIN: Tampilkan Modal Window via snap.js
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran - MustikaPay</title>
    <!-- Memuat script SNAP MustikaPay -->
    <script src="https://mustikapayment.com/assets/snap.js"></script>
</head>
<body style="background: #f1f5f9; font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0;">

    <div style="text-align: center; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        <h2 style="color: #0f172a; margin-bottom: 10px;">Menyiapkan Pembayaran...</h2>
        <p style="color: #64748b;">Silakan selesaikan pembayaran Anda pada jendela yang muncul.</p>
    </div>

    <script type="text/javascript">
        // Panggil snap.pay dengan token yang ada
        window.onload = function() {
            snap.pay('<?php echo htmlspecialchars($token); ?>', {
                onClose: function() {
                    // Logika jika modal ditutup oleh user
                    alert('Pembayaran dibatalkan.');
                    window.location.href = '/'; 
                }
            });
        };
    </script>
</body>
</html>
