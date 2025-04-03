<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../helpers/db.php';

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    die("Неверный идентификатор пользователя.");
}

$profileUserId = (int) $_GET['user_id'];

$stmt = $pdo->prepare("
    SELECT u.*, s.avatar_url, s.cover_url, s.status, s.bio, s.show_realname
    FROM users u
    LEFT JOIN users_settings s ON u.user_id = s.user_id
    WHERE u.user_id = :user_id
");
$stmt->execute(['user_id' => $profileUserId]);
$userProfile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userProfile) {
    die("Пользователь не найден.");
}

$isSubscribed = false;
$isOwner = false;
$subscriptionId = null;
if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $profileUserId) {
    $isOwner = true;
}
elseif (isset($_SESSION['user']) && $_SESSION['user']['id'] != $profileUserId) {
    $stmt = $pdo->prepare("SELECT subscription_id FROM subscriptions WHERE subscriber_id = :subscriber_id AND user_id = :user_id");
    $stmt->execute([
        'subscriber_id' => $_SESSION['user']['id'],
        'user_id' => $profileUserId
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $isSubscribed = true;
        $subscriptionId = $row['subscription_id'];
    }
}


$stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM subscriptions WHERE user_id = :user_id");
$stmt->execute(['user_id' => $profileUserId]);
$subsRow = $stmt->fetch(PDO::FETCH_ASSOC);
$subscriberCount = $subsRow ? (int)$subsRow['cnt'] : 0;

$stmt = $pdo->prepare("SELECT post_id FROM posts WHERE user_id = :user_id");
$stmt->execute(['user_id' => $profileUserId]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$allReactions = [];
if ($posts) {
    $postIds = array_column($posts, 'post_id');
    $inClause = implode(',', array_map('intval', $postIds));
    $stmt = $pdo->query("SELECT category FROM reactions WHERE post_id IN ($inClause)");
    $allReactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$reactionColors = [
    'like'      => [51, 204, 51],
    'dislike'   => [0, 0, 0],
    'angry'     => [255, 0, 0],
    'surprised' => [255, 255, 0],
    'happy'     => [0, 0, 255]
];

$totalCount = 0;
$totalR = 0; 
$totalG = 0; 
$totalB = 0;
foreach ($allReactions as $r) {
    $cat = $r['category'];
    if (isset($reactionColors[$cat])) {
        $totalR += $reactionColors[$cat][0];
        $totalG += $reactionColors[$cat][1];
        $totalB += $reactionColors[$cat][2];
        $totalCount++;
    }
}

if ($totalCount > 0) {
    $avgR = round($totalR / $totalCount);
    $avgG = round($totalG / $totalCount);
    $avgB = round($totalB / $totalCount);
    $eventColor = sprintf("#%02x%02x%02x", $avgR, $avgG, $avgB);
} else {
    $eventColor = "#cccccc";
}

$stmt = $pdo->prepare("
    SELECT p.* 
    FROM posts p
    WHERE p.user_id = :user_id
    ORDER BY p.created_at DESC
");
$stmt->execute(['user_id' => $profileUserId]);
$userPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../views/user/view.php';
