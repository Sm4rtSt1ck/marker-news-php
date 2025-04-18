<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($pageTitle ?? 'MarkerNews') ?></title>
  <!-- CSS -->
  <link rel="stylesheet" href="/assets/css/base.css">
  <link rel="stylesheet" href="/assets/css/components.css">
  <link rel="stylesheet" href="/assets/css/header.css">
  <link rel="stylesheet" href="/assets/css/footer.css">
  <?php if (!empty($pageStyles) && is_array($pageStyles)): ?>
    <?php foreach ($pageStyles as $cssPath): ?>
      <link rel="stylesheet" href="<?= htmlspecialchars($cssPath) ?>">
    <?php endforeach; ?>
  <?php endif; ?>
  <!-- Scripts -->
  <?php if (!empty($pageScripts) && is_array($pageScripts)): ?>
    <?php foreach ($pageScripts as $scriptPath): ?>
      <script
        src="<?= htmlspecialchars($scriptPath) ?>"
        defer
      ></script>
    <?php endforeach; ?>
  <?php endif; ?>
</head>

<body>
  <header>
    <div class="header-container">
      <h1><a href="/">MarkerNews</a></h1>
      <nav>
        <ul>
          <?php if (isset($_SESSION['user'])): ?>
            <li><a href="/feed">Лента</a></li>
            <li><a href="/subscriptions">Подписки</a></li>
            <li><a href="/event/create">Создать событие</a></li>
            <li><a href="/profile">Профиль</a></li>
            <li><a href="/settings">Настройки</a></li>
            <li><a href="/logout">Выход</a></li>
            <?php else: ?>
              <li><a href="/">Главная</a></li>
            <li><a href="/login">Вход</a></li>
            <li><a href="/register">Регистрация</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>
