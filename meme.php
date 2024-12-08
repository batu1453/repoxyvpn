<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Formdan gelen verileri al
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $amount = htmlspecialchars($_POST['amount']);

    // Tami API ayarları
    $api_url = "https://api.tami.com.tr/odeme";
    $api_key = "SIZIN_API_ANAHTARINIZ";

    // API'ye gönderilecek veri
    $data = [
        "tutar" => (float)$amount,
        "para_birimi" => "TRY",
        "odeme_metodu" => "KREDI_KARTI",
        "musteri_bilgisi" => [
            "ad" => $name,
            "email" => $email,
        ],
    ];

    // cURL ile API'ye istek gönder
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Sonuç mesajını ayarla
    if ($http_code == 200) {
        $message = "Ödeme başarıyla tamamlandı!";
    } else {
        $message = "Ödeme işlemi başarısız oldu: " . $response;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sayfası</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .payment-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        .payment-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-btn {
            background: #28a745;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .submit-btn:hover {
            background: #218838;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Ödeme Yap</h2>
        <?php if (!empty($message)): ?>
            <div class="message <?= $http_code == 200 ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Ad Soyad</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">E-Posta</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="amount">Tutar (₺)</label>
                <input type="number" id="amount" name="amount" step="0.01" required>
            </div>
            <button type="submit" class="submit-btn">Ödeme Yap</button>
        </form>
    </div>
</body>
</html>
