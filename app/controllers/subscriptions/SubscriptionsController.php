<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}

require_once __DIR__ . '/../../helpers/db.php';

$subscriber_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("
    SELECT s.*, 
           u.first_name, u.last_name, u.nickname, us.avatar_url AS user_avatar, us.show_realname,
           m.name AS media_name, m.avatar_url AS media_avatar
    FROM subscriptions s
    LEFT JOIN users u ON s.user_id = u.user_id
    LEFT JOIN users_settings us ON u.user_id = us.user_id
    LEFT JOIN media_outlets m ON s.media_id = m.media_id
    WHERE s.subscriber_id = :subscriber_id
    ORDER BY s.created_at DESC
");
$stmt->execute(['subscriber_id' => $subscriber_id]);
$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../../views/subscriptions/view.php';
