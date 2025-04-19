<?php



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



require_once __DIR__ . '/../../helpers/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    
    $stmt = $pdo->query("SELECT country_code, country_name FROM countries ORDER BY country_name ASC");
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    include __DIR__ . '/../../views/auth/register.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $first_name   = trim($_POST['first_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $nickname     = trim($_POST['nickname'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $password     = $_POST['password'] ?? '';
    $birth_date   = $_POST['birth_date'] ?? '';
    $country_code = $_POST['country'] ?? ''; 
    $city         = trim($_POST['city'] ?? '');
    $display_name = $_POST['display_name'] ?? 'full'; 

    $errors = [];

    
    if (empty($first_name))   { $errors[] = "Введите имя."; }
    if (empty($last_name))    { $errors[] = "Введите фамилию."; }
    if (empty($email))        { $errors[] = "Введите электронную почту."; }
    if (empty($phone))        { $errors[] = "Введите номер телефона."; }
    if (empty($password))     { $errors[] = "Введите пароль."; }
    if (empty($birth_date))   { $errors[] = "Введите дату рождения."; }
    if (empty($country_code)) { $errors[] = "Выберите страну."; }
    if (empty($city))         { $errors[] = "Введите город."; }

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Неверный формат электронной почты.";
    }

    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM countries WHERE country_code = :country_code");
    $stmt->execute(['country_code' => $country_code]);
    if ($stmt->fetchColumn() == 0) {
        $errors[] = "Выбранная страна недействительна.";
    }

    
    if (count($errors) > 0) {
        $stmt = $pdo->query("SELECT country_code, country_name FROM countries ORDER BY country_name ASC");
        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../../views/auth/register.php';
        exit;
    }

    
    if (empty($nickname)) {
        $base_nickname = strtolower($first_name . $last_name);
        $nickname = $base_nickname . rand(100, 999);

        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE nickname = :nickname");
        $attempts = 0;
        while (true) {
            $stmt->execute(['nickname' => $nickname]);
            if ($stmt->fetchColumn() == 0) {
                break;
            }
            $nickname = $base_nickname . rand(1000, 9999);
            $attempts++;
            if ($attempts > 10) {
                $errors[] = "Не удалось сгенерировать уникальный никнейм, пожалуйста, введите никнейм вручную.";
                break;
            }
        }
    }

    
    if (count($errors) > 0) {
        $stmt = $pdo->query("SELECT country_code, country_name FROM countries ORDER BY country_name ASC");
        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../../views/auth/register.php';
        exit;
    }

    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, nickname, email, phone, password_hash, birth_date, country_code, city) VALUES (:first_name, :last_name, :nickname, :email, :phone, :password_hash, :birth_date, :country_code, :city)");
    try {
        $stmt->execute([
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'nickname'      => $nickname,
            'email'         => $email,
            'phone'         => $phone,
            'password_hash' => $password_hash,
            'birth_date'    => $birth_date,
            'country_code'  => $country_code,
            'city'          => $city
        ]);
        
        $user_id = $pdo->lastInsertId();
    } catch (PDOException $e) {
        
        $errors[] = "Ошибка регистрации: " . $e->getMessage();
        $stmt = $pdo->query("SELECT country_code, country_name FROM countries ORDER BY country_name ASC");
        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../../views/auth/register.php';
        exit;
    }

    
    $show_realname = ($display_name === 'full') ? 1 : 0;

    
    $stmt = $pdo->prepare("INSERT INTO users_settings (user_id, show_realname) VALUES (:user_id, :show_realname)");
    $stmt->execute([
        'user_id'      => $user_id,
        'show_realname'=> $show_realname
    ]);

    
    $_SESSION['success'] = "Регистрация прошла успешно. Теперь вы можете войти в систему.";
    header("Location: /login");
    exit;
}
