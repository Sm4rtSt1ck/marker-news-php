<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}

require_once __DIR__ . '/../../helpers/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $news_type = trim($_POST['news_type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($name)) {
        $errors[] = "Наименование СМИ обязательно.";
    } elseif (mb_strlen($name) > 100) {
        $errors[] = "Наименование СМИ не должно превышать 100 символов.";
    }

    if (empty($news_type)) {
        $errors[] = "Тип новостей обязателен.";
    }
    $allowedNewsTypes = ["Entertainment", "Political", "Economic", "Sports", "Technology", "Cultural", "Scientific", "Social", "Other"];
    if (!in_array($news_type, $allowedNewsTypes)) {
        $errors[] = "Выбран некорректный тип новостей.";
    }

    if (empty($description)) {
        $errors[] = "Описание организации обязательно.";
    } elseif (mb_strlen($description) > 10000) {
        $errors[] = "Описание организации не должно превышать 10000 символов.";
    }

    if (empty($email)) {
        $errors[] = "Электронная почта обязательна.";
    } elseif (mb_strlen($email) > 100) {
        $errors[] = "Электронная почта не должна превышать 100 символов.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный формат электронной почты.";
    }

    if (empty($phone)) {
        $errors[] = "Номер телефона обязателен.";
    } elseif (mb_strlen($phone) > 20) {
        $errors[] = "Номер телефона не должен превышать 20 символов.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO media_outlets (owner_id, name, description, email, phone, news_type) VALUES (:owner_id, :name, :description, :email, :phone, :news_type)");
        try {
            $stmt->execute([
                'owner_id'  => $_SESSION['user']['id'],
                'name'      => $name,
                'description'=> $description,
                'email'     => $email,
                'phone'     => $phone,
                'news_type' => $news_type
            ]);
            $_SESSION['success'] = "СМИ успешно зарегистрировано.";
            header("Location: /profile");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Ошибка при регистрации СМИ: " . $e->getMessage();
        }
    }
}

include __DIR__ . '/../../views/media/register.php';
