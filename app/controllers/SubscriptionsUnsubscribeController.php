<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Необходима авторизация.']);
    exit;
}

require_once __DIR__ . '/../helpers/db.php';

$subscription_id = $_POST['subscription_id'] ?? '';

if (empty($subscription_id) || !is_numeric($subscription_id)) {
    echo json_encode(['error' => 'Неверный идентификатор подписки.']);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM subscriptions WHERE subscription_id = :subscription_id AND subscriber_id = :subscriber_id");
try {
    $stmt->execute(['subscription_id' => $subscription_id, 'subscriber_id' => $_SESSION['user']['id']]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Подписка не найдена или вы не можете отписаться.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Ошибка при удалении подписки.']);
}
