<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Необходимо авторизоваться.']);
    exit;
}

require_once __DIR__ . '/../../helpers/db.php';

$user_id = $_SESSION['user']['id'];

$action = $_POST['action'] ?? '';

if ($action === 'like') {
    $post_id = $_POST['post_id'] ?? '';
    $reaction = $_POST['reaction'] ?? 'like'; $stmt = $pdo->prepare("INSERT INTO reactions (user_id, post_id, category) VALUES (:user_id, :post_id, :category)");
    try {
        $stmt->execute([
            'user_id'  => $user_id,
            'post_id'  => $post_id,
            'category' => $reaction
        ]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Ошибка при добавлении реакции.']);
        exit;
    }
    
    echo json_encode(['success' => true, 'reaction_color' => '#33cc33']);
    exit;
}

elseif ($action === 'comment') {
    $post_id = $_POST['post_id'] ?? '';
    $comment_text = trim($_POST['comment_text'] ?? '');
    
    if (empty($comment_text)) {
        echo json_encode(['error' => 'Комментарий не может быть пустым.']);
        exit;
    }
    if (mb_strlen($comment_text) > 5000) {
        echo json_encode(['error' => 'Комментарий не должен превышать 5000 символов.']);
        exit;
    }
    
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, text) VALUES (:user_id, :post_id, :text)");
    try {
        $stmt->execute([
            'user_id' => $user_id,
            'post_id' => $post_id,
            'text'    => $comment_text
        ]);
        $comment_id = $pdo->lastInsertId();
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Ошибка при добавлении комментария.']);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT u.first_name, u.last_name, u.nickname, s.avatar_url, c.created_at 
                           FROM comments c 
                           JOIN users u ON c.user_id = u.user_id 
                           LEFT JOIN users_settings s ON u.user_id = s.user_id 
                           WHERE c.comment_id = :comment_id");
    $stmt->execute(['comment_id' => $comment_id]);
    $newComment = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($newComment) {
        $displayName = (isset($newComment['first_name']) && isset($newComment['last_name'])) 
                       ? htmlspecialchars($newComment['first_name'] . ' ' . $newComment['last_name']) 
                       : htmlspecialchars($newComment['nickname']);
        $newComment['author_name'] = $displayName;
    }
    
    echo json_encode(['success' => true, 'comment' => $newComment]);
    exit;
}

else {
    echo json_encode(['error' => 'Неверное действие.']);
    exit;
}
