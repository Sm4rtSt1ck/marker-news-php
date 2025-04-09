<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../helpers/db.php';

$type = $_GET['type'] ?? 'all';
$country = $_GET['country'] ?? '';
$city = trim($_GET['city'] ?? '');
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$category = $_GET['category'] ?? '';
$subscription_filter = $_GET['subscription'] ?? 'any';
$search_title = trim($_GET['search_title'] ?? '');
$search_location = trim($_GET['search_location'] ?? '');

$where = [];
$params = [];

if ($type === 'news') {
    $where[] = "posts.media_id IS NOT NULL";
} elseif ($type === 'events') {
    $where[] = "posts.user_id IS NOT NULL";
}

if (!empty($country)) {
    $where[] = "EXISTS (SELECT 1 FROM users u WHERE u.user_id = posts.user_id AND u.country_code = :country)";
    $params['country'] = $country;
}

if (!empty($city)) {
    $where[] = "EXISTS (SELECT 1 FROM users u WHERE u.user_id = posts.user_id AND u.city LIKE :city)";
    $params['city'] = "%" . $city . "%";
}

if (!empty($date_from)) {
    $where[] = "posts.created_at >= :date_from";
    $params['date_from'] = $date_from;
}
if (!empty($date_to)) {
    $where[] = "posts.created_at <= :date_to";
    $params['date_to'] = $date_to;
}

if (!empty($category)) {
    $where[] = "posts.category = :category";
    $params['category'] = $category;
}

if ($subscription_filter === 'subscriptions' && isset($_SESSION['user'])) {
    $currentUserId = $_SESSION['user']['id'];
    $where[] = "((posts.user_id IS NOT NULL AND posts.user_id IN (SELECT user_id FROM subscriptions WHERE subscriber_id = :currentUserId))
                  OR (posts.media_id IS NOT NULL AND posts.media_id IN (SELECT media_id FROM subscriptions WHERE subscriber_id = :currentUserId)))";
    $params['currentUserId'] = $currentUserId;
}

if (!empty($search_title)) {
    $where[] = "posts.title LIKE :search_title";
    $params['search_title'] = "%" . $search_title . "%";
}

if (!empty($search_location)) {
    $where[] = "posts.address LIKE :search_location";
    $params['search_location'] = "%" . $search_location . "%";
}

$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$sql = "SELECT posts.*, 
               u.first_name, u.last_name, u.nickname, us.avatar_url AS user_avatar, us.show_realname,
               m.name AS media_name, m.avatar_url AS media_avatar
        FROM posts
        LEFT JOIN users u ON posts.user_id = u.user_id
        LEFT JOIN users_settings us ON u.user_id = us.user_id
        LEFT JOIN media_outlets m ON posts.media_id = m.media_id
        $whereSQL
        ORDER BY posts.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT country_code, country_name FROM countries ORDER BY country_name ASC");
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../views/feed/index.php';
