<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../helpers/db.php';

if (!isset($_GET['media_id']) || !is_numeric($_GET['media_id'])) {
    die("Неверный идентификатор СМИ.");
}

$media_id = (int) $_GET['media_id'];

$stmt = $pdo->prepare("SELECT * FROM media_outlets WHERE media_id = :media_id");
$stmt->execute(['media_id' => $media_id]);
$media = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$media) {
    die("СМИ не найдено.");
}

$isOwnerOrModerator = false;
if (isset($_SESSION['user'])) {
    $currentUserId = $_SESSION['user']['id'];
    if ($currentUserId == $media['owner_id']) {
        $isOwnerOrModerator = true;
    } else {
        $stmt = $pdo->prepare("SELECT * FROM media_editors WHERE media_id = :media_id AND user_id = :user_id");
        $stmt->execute(['media_id' => $media_id, 'user_id' => $currentUserId]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $isOwnerOrModerator = true;
        }
    }
}

$stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM subscriptions WHERE media_id = :media_id");
$stmt->execute(['media_id' => $media_id]);
$subsRow = $stmt->fetch(PDO::FETCH_ASSOC);
$subscriberCount = $subsRow ? (int)$subsRow['cnt'] : 0;

$stmt = $pdo->prepare("SELECT post_id FROM posts WHERE media_id = :media_id");
$stmt->execute(['media_id' => $media_id]);
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
    $avgColor = sprintf("#%02x%02x%02x", $avgR, $avgG, $avgB);
} else {
    $avgColor = "#cccccc";
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE media_id = :media_id ORDER BY created_at DESC");
$stmt->execute(['media_id' => $media_id]);
$newsPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../views/media/view.php';
