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

$subscriber_id = $_SESSION['user']['id'];

if (isset($_POST['subscription_type']) && isset($_POST['subscription_target'])) {
    $subscription_type = $_POST['subscription_type'];
    $subscription_target = $_POST['subscription_target'];

    if ($subscription_type === 'user') {
        $stmt = $pdo->prepare("SELECT subscription_id FROM subscriptions WHERE subscriber_id = :subscriber_id AND user_id = :user_id");
        $stmt->execute([
            'subscriber_id' => $subscriber_id,
            'user_id'       => $subscription_target
        ]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            echo json_encode(['error' => 'Вы уже подписаны на этого пользователя.']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO subscriptions (subscriber_id, user_id) VALUES (:subscriber_id, :user_id)");
        try {
            $stmt->execute([
                'subscriber_id' => $subscriber_id,
                'user_id'       => $subscription_target
            ]);
            echo json_encode(['success' => true]);
            exit;
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Ошибка подписки: ' . $e->getMessage()]);
            exit;
        }
    } elseif ($subscription_type === 'media') {
        $stmt = $pdo->prepare("SELECT subscription_id FROM subscriptions WHERE subscriber_id = :subscriber_id AND media_id = :media_id");
        $stmt->execute([
            'subscriber_id' => $subscriber_id,
            'media_id'      => $subscription_target
        ]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            echo json_encode(['error' => 'Вы уже подписаны на это СМИ.']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO subscriptions (subscriber_id, media_id) VALUES (:subscriber_id, :media_id)");
        try {
            $stmt->execute([
                'subscriber_id' => $subscriber_id,
                'media_id'      => $subscription_target
            ]);
            echo json_encode(['success' => true]);
            exit;
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Ошибка подписки: ' . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['error' => 'Неверный тип подписки.']);
        exit;
    }
} else {
    echo json_encode(['error' => 'Неверные параметры запроса.']);
    exit;
}
