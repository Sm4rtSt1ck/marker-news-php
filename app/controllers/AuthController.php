<?php
require_once __DIR__ . '/../helpers/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $emailOrPhone = isset($_POST['email_or_phone']) ? trim($_POST['email_or_phone']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($emailOrPhone) || empty($password)) {
        $error = "Пожалуйста, заполните все поля.";
    } else {
        if (filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL)) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :identifier LIMIT 1");
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = :identifier LIMIT 1");
        }
        $stmt->execute(['identifier' => $emailOrPhone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id'         => $user['user_id'],
                'email'      => $user['email'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'nickname'   => $user['nickname']
            ];

            header("Location: /feed");
            exit;
        } else {
            $error = "Неверные учетные данные.";
        }
    }
}

include __DIR__ . '/../views/auth/login.php';
