<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/header.php'; ?>
    <div class="login-container">
        <h2>Вход в систему</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="/login" method="POST">
            <div>
                <label for="email_or_phone">Почта или номер:</label>
                <input type="text" id="email_or_phone" name="email_or_phone" required>
            </div>
            <div>
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Войти</button>
            </div>
        </form>
    </div>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
