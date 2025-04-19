<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../helpers/db.php';

if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    echo "Неверный идентификатор поста.";
    exit;
}

$post_id = (int) $_GET['post_id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = :post_id LIMIT 1");
$stmt->execute(['post_id' => $post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "Пост не найден.";
    exit;
}

$author = null;
$media = null;
if (!empty($post['user_id'])) {
    $stmt = $pdo->prepare("SELECT u.*, s.avatar_url, s.cover_url, s.show_realname 
                           FROM users u 
                           LEFT JOIN users_settings s ON u.user_id = s.user_id 
                           WHERE u.user_id = :user_id");
    $stmt->execute(['user_id' => $post['user_id']]);
    $author = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (!empty($post['media_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM media_outlets WHERE media_id = :media_id");
    $stmt->execute(['media_id' => $post['media_id']]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
}

$stmt = $pdo->prepare("SELECT * FROM attachments WHERE post_id = :post_id");
$stmt->execute(['post_id' => $post_id]);
$attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT c.*, u.first_name, u.last_name, u.nickname, s.avatar_url, s.show_realname,
                       CASE WHEN s.show_realname = 0 THEN u.nickname ELSE CONCAT(u.first_name, ' ', u.last_name) END AS author_name
                       FROM comments c 
                       JOIN users u ON c.user_id = u.user_id 
                       LEFT JOIN users_settings s ON u.user_id = s.user_id 
                       WHERE c.post_id = :post_id 
                       ORDER BY c.created_at DESC");
$stmt->execute(['post_id' => $post_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);


$reactionColors = [
    'like'      => [51, 204, 51],   'dislike'   => [0, 0, 0],       'angry'     => [255, 0, 0],     'surprised' => [255, 255, 0],   'happy'     => [0, 0, 255]      ];

$stmt = $pdo->query("SHOW COLUMNS FROM reactions LIKE 'category'");
$col = $stmt->fetch(PDO::FETCH_ASSOC);
if ($col) {
    preg_match_all("/'(.*?)'/", $col['Type'], $matches);
    $reactionVariants = $matches[1];
} else {
    $reactionVariants = [];
}


$stmt = $pdo->prepare("SELECT category, COUNT(*) AS cnt FROM reactions WHERE post_id = :post_id GROUP BY category");
$stmt->execute(['post_id' => $post_id]);
$reactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalCount = 0;
$totalR = 0;
$totalG = 0;
$totalB = 0;

foreach ($reactions as $reaction) {
    $cat = $reaction['category'];
    $cnt = (int) $reaction['cnt'];
    if (isset($reactionColors[$cat])) {
        $totalR += $reactionColors[$cat][0] * $cnt;
        $totalG += $reactionColors[$cat][1] * $cnt;
        $totalB += $reactionColors[$cat][2] * $cnt;
        $totalCount += $cnt;
    }
}

if ($totalCount > 0) {
    $avgR = round($totalR / $totalCount);
    $avgG = round($totalG / $totalCount);
    $avgB = round($totalB / $totalCount);
    $reaction_color = sprintf("#%02x%02x%02x", $avgR, $avgG, $avgB);
} else {
    $reaction_color = "#cccccc"; }

include __DIR__ . '/../../views/post/view.php';
