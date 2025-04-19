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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
        echo "Неверный идентификатор поста.";
        exit;
    }
    $post_id = (int) $_GET['post_id'];

    $stmt = $pdo->query("SHOW COLUMNS FROM reports LIKE 'category'");
    $col = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($col) {
        $enumStr = substr($col['Type'], 5, -1);
        $enumStr = trim($enumStr, "'");
        $reportCategories = explode("','", $enumStr);        
        $categories = explode("','", $enumStr);
    } else {
        $categories = [];
    }
    
    include __DIR__ . '/../../views/report/create.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if ($post_id <= 0) {
        $errors[] = "Неверный идентификатор поста.";
    }
    if (empty($category)) {
        $errors[] = "Выберите категорию жалобы.";
    }
    if (mb_strlen($description) > 10000) {
        $errors[] = "Описание не должно превышать 10000 символов.";
    }

    $stmt = $pdo->query("SHOW COLUMNS FROM reports LIKE 'category'");
    $col = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($col) {
        $enumStr = substr($col['Type'], 5, -1);
        $enumStr = trim($enumStr, "'");
        $validCategories = explode("','", $enumStr);
        if (!in_array($category, $validCategories)) {
            $errors[] = "Неверная категория жалобы.";
        }
    } else {
        $errors[] = "Ошибка при проверке категории.";
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO reports (reporter_id, post_id, category, description) VALUES (:reporter_id, :post_id, :category, :description)");
        try {
            $stmt->execute([
                'reporter_id' => $_SESSION['user']['id'],
                'post_id'     => $post_id,
                'category'    => $category,
                'description' => $description
            ]);
            $_SESSION['success'] = "Жалоба успешно отправлена.";
            header("Location: /post/view?post_id=" . $post_id);
            exit;
        } catch (PDOException $e) {
            $errors[] = "Ошибка при отправке жалобы: " . $e->getMessage();
        }
    }

    $stmt = $pdo->query("SHOW COLUMNS FROM reports LIKE 'category'");
    $col = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($col) {
        $enumStr = substr($col['Type'], 5, -1);
        $enumStr = trim($enumStr, "'");
        $categories = explode("','", $enumStr);
    } else {
        $categories = [];
    }
    
    include __DIR__ . '/../../views/report/create.php';
    exit;
}
