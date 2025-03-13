<?php
require_once __DIR__ . '/../helpers/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $emailOrPhone = isset($_POST['email_or_phone']) ? trim($_POST['email_or_phone']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($emailOrPhone) || empty($password)) {
        $error = "Пожалуйста, заполните все поля.";
    } else {
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $emailOrPhone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {

            $_SESSION['user'] = [
                'id'    => $user['id'],
                'email' => $user['email'],
                'name'  => $user['name'] 
            ];
            
            header("Location: /");
            exit;
        } else {
            $error = "Неверные учетные данные.";
        }
    }
}


include __DIR__ . '/../views/auth/login.php';
