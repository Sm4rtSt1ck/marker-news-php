<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}

require_once __DIR__ . '/../helpers/db.php';

$user_id = $_SESSION['user']['id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Personal info
    if (isset($_POST['update_personal_data'])) {
        $first_name   = trim($_POST['first_name'] ?? '');
        $last_name    = trim($_POST['last_name'] ?? '');
        $nickname     = trim($_POST['nickname'] ?? '');
        $email        = trim($_POST['email'] ?? '');
        $phone        = trim($_POST['phone'] ?? '');
        $birth_date   = trim($_POST['birth_date'] ?? '');
        $display_name = $_POST['display_name'] ?? 'full';
        $country_code = $_POST['country'] ?? '';
        $city         = trim($_POST['city'] ?? '');
        $passport     = trim($_POST['passport'] ?? '');

        if (empty($first_name))   { $errors[] = "Имя не может быть пустым."; }
        if (empty($last_name))    { $errors[] = "Фамилия не может быть пустой."; }
        if (empty($nickname))     { $errors[] = "Никнейм не может быть пустым."; }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Некорректная почта."; }
        if (empty($phone))        { $errors[] = "Телефон не может быть пустым."; }
        if (empty($birth_date))   { $errors[] = "Дата рождения не может быть пустой."; }
        if (empty($country_code)) { $errors[] = "Страна не может быть пустой."; }
        if (empty($city))         { $errors[] = "Город не может быть пустым."; }

        // Check for country
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM countries WHERE country_code = :country_code");
        $stmt->execute(['country_code' => $country_code]);
        if ($stmt->fetchColumn() == 0) {
            $errors[] = "Выбранная страна недействительна.";
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE users 
                                   SET first_name = :first_name, 
                                       last_name = :last_name, 
                                       nickname = :nickname, 
                                       email = :email, 
                                       phone = :phone, 
                                       birth_date = :birth_date, 
                                       country_code = :country_code, 
                                       city = :city, 
                                       updated_at = CURRENT_TIMESTAMP 
                                   WHERE user_id = :user_id");
            $stmt->execute([
                'first_name'   => $first_name,
                'last_name'    => $last_name,
                'nickname'     => $nickname,
                'email'        => $email,
                'phone'        => $phone,
                'birth_date'   => $birth_date,
                'country_code' => $country_code,
                'city'         => $city,
                'user_id'      => $user_id
            ]);

            $show_realname = ($display_name === 'full') ? 1 : 0;
            $stmt = $pdo->prepare("UPDATE users_settings SET show_realname = :show_realname WHERE user_id = :user_id");
            $stmt->execute([
                'show_realname' => $show_realname,
                'user_id'       => $user_id
            ]);

            $_SESSION['success'] = "Персональные данные успешно обновлены.";
        }
    }
    // Updating profile
    elseif (isset($_POST['update_profile'])) {
        $status = trim($_POST['status'] ?? '');
        $bio    = trim($_POST['bio'] ?? '');

        $stmt = $pdo->prepare("UPDATE users_settings 
                               SET status = :status, 
                                   bio = :bio 
                               WHERE user_id = :user_id");
        $stmt->execute([
            'status'  => $status,
            'bio'     => $bio,
            'user_id' => $user_id
        ]);

        $uploadDir = __DIR__ . '/../../public/uploads/';
        // Avatar
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarName = basename($_FILES['avatar']['name']);
            $avatarNewName = uniqid('avatar_') . "_" . $avatarName;
            $targetAvatarPath = $uploadDir . $avatarNewName;
            if (move_uploaded_file($avatarTmp, $targetAvatarPath)) {
                $avatar_url = '/uploads/' . $avatarNewName;
                $stmt = $pdo->prepare("UPDATE users_settings SET avatar_url = :avatar_url WHERE user_id = :user_id");
                $stmt->execute([
                    'avatar_url' => $avatar_url,
                    'user_id'    => $user_id
                ]);
            } else {
                $errors[] = "Ошибка загрузки аватара.";
            }
        }
        // Cover
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $coverTmp = $_FILES['cover']['tmp_name'];
            $coverName = basename($_FILES['cover']['name']);
            $coverNewName = uniqid('cover_') . "_" . $coverName;
            $targetCoverPath = $uploadDir . $coverNewName;
            if (move_uploaded_file($coverTmp, $targetCoverPath)) {
                $cover_url = '/uploads/' . $coverNewName;
                $stmt = $pdo->prepare("UPDATE users_settings SET cover_url = :cover_url WHERE user_id = :user_id");
                $stmt->execute([
                    'cover_url' => $cover_url,
                    'user_id'   => $user_id
                ]);
            } else {
                $errors[] = "Ошибка загрузки шапки.";
            }
        }

        if (empty($errors)) {
            $_SESSION['success'] = "Профиль успешно обновлён.";
        }
    }
    // Password
    elseif (isset($_POST['change_password'])) {
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_new_password = $_POST['confirm_new_password'] ?? '';

        if (empty($old_password) || empty($new_password) || empty($confirm_new_password)) {
            $errors[] = "Для смены пароля заполните все поля.";
        } else {
            if (strlen($new_password) < 8 || strlen($new_password) > 30) {
                $errors[] = "Новый пароль должен быть от 8 до 30 символов.";
            }
            if ($new_password !== $confirm_new_password) {
                $errors[] = "Новый пароль и подтверждение не совпадают.";
            }
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && !password_verify($old_password, $row['password_hash'])) {
                $errors[] = "Старый пароль указан неверно.";
            }
            if (empty($errors)) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password_hash = :new_hash, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id");
                $stmt->execute([
                    'new_hash' => $new_hash,
                    'user_id'  => $user_id
                ]);
                $_SESSION['success'] = "Пароль успешно изменён.";
            }
        }
    }

    if (!empty($errors)) {
        $user['errors'] = $errors;
    }
}

// Reload data
$stmt = $pdo->prepare("SELECT u.*, s.avatar_url, s.cover_url, s.bio, s.status, s.show_realname, s.language 
                       FROM users u 
                       LEFT JOIN users_settings s ON u.user_id = s.user_id 
                       WHERE u.user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$countriesStmt = $pdo->query("SELECT country_code, country_name FROM countries ORDER BY country_name ASC");
$countries = $countriesStmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../views/user/settings.php';
