<?php
// Настройки подключения к базе данных Open Server
$host = '127.0.1.31'; // IP-адрес MySQL из твоих логов Open Server
$db   = 'group_blog';
$user = 'root';
$pass = ''; // В Open Server по умолчанию пароль пустой
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Подключаемся к MySQL
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Проверяем, что данные отправлены через форму
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['sender_name']);
    $email = trim($_POST['sender_email']);
    $text = trim($_POST['message_text']);

    if (!empty($name) && !empty($email) && !empty($text)) {
        // Подготавливаем безопасный SQL-запрос для вставки данных
        $stmt = $pdo->prepare("INSERT INTO messages (sender_name, sender_email, message_text) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $text]);

        // Перенаправляем пользователя обратно на главную страницу с сообщением об успехе
        echo "<script>alert('Сообщение успешно отправлено!'); window.location.href='index.html';</script>";
    } else {
        echo "Пожалуйста, заполните все поля.";
    }
}
?>