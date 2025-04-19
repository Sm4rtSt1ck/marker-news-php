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

if (!isset($_GET['media_id']) || !is_numeric($_GET['media_id'])) {
    die("Ошибка: не указан идентификатор СМИ.");
}

$media_id = (int)$_GET['media_id'];

$stmt = $pdo->prepare("SELECT * FROM media_outlets WHERE media_id = :media_id AND owner_id = :owner_id LIMIT 1");
$stmt->execute([
    'media_id'  => $media_id,
    'owner_id'  => $_SESSION['user']['id']
]);
$media = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$media) {
    die("Ошибка: вы не являетесь владельцем данного СМИ или СМИ не существует.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $latitude = trim($_POST['latitude'] ?? '');
    $longitude = trim($_POST['longitude'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (empty($title)) {
        $errors[] = "Заголовок обязателен.";
    } elseif (mb_strlen($title) > 255) {
        $errors[] = "Заголовок не должен превышать 255 символов.";
    }
    
    if (empty($description)) {
        $errors[] = "Описание обязательно.";
    } elseif (mb_strlen($description) > 30000) {
        $errors[] = "Описание не должно превышать 30000 символов.";
    }

    $allowedCategories = ['good', 'bad', 'neutral', 'funny', 'sad', 'strange'];
    if (empty($category) || !in_array($category, $allowedCategories)) {
        $errors[] = "Выберите корректный характер новости.";
    }

    if ($latitude === '' || $longitude === '') {
        $latitude = null;
        $longitude = null;
    }

    $attachments = [];
    if (isset($_FILES['attachments']) && $_FILES['attachments']['error'][0] != UPLOAD_ERR_NO_FILE) {
        $fileCount = count($_FILES['attachments']['name']);
        if ($fileCount > 20) {
            $errors[] = "Максимальное количество вложений: 20.";
        } else {
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['attachments']['tmp_name'][$i];
                    $originalName = basename($_FILES['attachments']['name'][$i]);
                    $uniqueName = uniqid('attachment_') . "_" . $originalName;
                    $uploadDir = __DIR__ . '/../../../public/uploads/';
                    $targetPath = $uploadDir . $uniqueName;
                    
                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $mime = mime_content_type($targetPath);
                        $fileType = 'document';
                        if (strpos($mime, 'image/') === 0) {
                            $fileType = 'image';
                        } elseif (strpos($mime, 'video/') === 0) {
                            $fileType = 'video';
                        } elseif (strpos($mime, 'audio/') === 0) {
                            $fileType = 'audio';
                        }
                        $attachments[] = [
                            'file_url' => '/uploads/' . $uniqueName,
                            'file_type' => $fileType
                        ];
                    } else {
                        $errors[] = "Ошибка загрузки файла: " . htmlspecialchars($originalName);
                    }
                } else {
                    $errors[] = "Ошибка загрузки файла: " . htmlspecialchars($_FILES['attachments']['name'][$i]);
                }
            }
        }
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO posts (media_id, title, description, category, latitude, longitude) VALUES (:media_id, :title, :description, :category, :latitude, :longitude)");
        $stmt->execute([
            'media_id'    => $media_id,
            'title'       => $title,
            'description' => $description,
            'category'    => $category,
            'latitude'    => $latitude,
            'longitude'   => $longitude
        ]);
        $post_id = $pdo->lastInsertId();

        if (!empty($attachments)) {
            $stmtAttachment = $pdo->prepare("INSERT INTO attachments (post_id, file_url, file_type) VALUES (:post_id, :file_url, :file_type)");
            foreach ($attachments as $att) {
                $stmtAttachment->execute([
                    'post_id'   => $post_id,
                    'file_url'  => $att['file_url'],
                    'file_type' => $att['file_type']
                ]);
            }
        }
        
        $_SESSION['success'] = "Новость успешно создана.";
        header("Location: /media/view?media_id=" . $media_id);
        exit;
    } else {
        $error = implode("<br>", $errors);
    }
}

include __DIR__ . '/../../views/news/create.php';
