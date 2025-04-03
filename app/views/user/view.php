<!-- app/views/user/view.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Страница пользователя</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user_view.css">
  <script src="/assets/js/user/user_view.js"></script>
</head>
<body>
<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="user-page-container">

  <div class="user-cover">
    <img src="<?php echo htmlspecialchars($userProfile['cover_url'] ?? '/assets/images/default_cover.jpg'); ?>" alt="Шапка пользователя">
  </div>

  <div class="user-avatar">
    <img src="<?php echo htmlspecialchars($userProfile['avatar_url'] ?? '/assets/images/default_avatar.png'); ?>" alt="Аватар пользователя">
  </div>

  <?php
    $showRealname = isset($userProfile['show_realname']) ? (bool)$userProfile['show_realname'] : true;
    if ($showRealname) {
      $displayName = htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']);
    } else {
      $displayName = htmlspecialchars($userProfile['nickname']);
    }
  ?>
  <h1 class="user-display-name"><?php echo $displayName; ?></h1>

  <?php if ($isOwner): ?>
    <div class="user-info">
      <p><strong>Страна:</strong> <?php echo htmlspecialchars($userProfile['country_code'] ?? ''); ?>,
         <strong>Город:</strong> <?php echo htmlspecialchars($userProfile['city'] ?? ''); ?></p>

      <p><strong>Статус:</strong> <?php echo htmlspecialchars($userProfile['status'] ?? ''); ?></p>

      <?php
        $bio = $userProfile['bio'] ?? '';
        $bioShort = mb_substr($bio, 0, 200);
        $bioIsLong = (mb_strlen($bio) > 200);
      ?>
      <p id="bio-short">
        <?php echo nl2br(htmlspecialchars($bioShort)); ?>
        <?php if ($bioIsLong): ?>
          ... <a href="#" id="bio-read-more" onclick="showFullBio(event)">раскрыть</a>
        <?php endif; ?>
      </p>
      <?php if ($bioIsLong): ?>
        <p id="bio-full" style="display: none;">
          <?php echo nl2br(htmlspecialchars($bio)); ?>
          <a href="#" onclick="hideFullBio(event)">свернуть</a>
        </p>
      <?php endif; ?>

      <p><a href="/settings" class="btn-settings">Перейти в настройки профиля</a></p>

      <p><strong>Подписчики:</strong> <?php echo (int)$subscriberCount; ?></p>

      <p><strong>Средний цвет событий:</strong> <span style="color: <?php echo htmlspecialchars($eventColor); ?>;"><?php echo $eventColor; ?></span></p>

      <h2>Мои события</h2>
      <?php if (!empty($userPosts)): ?>
        <?php foreach ($userPosts as $post): ?>
          <?php include __DIR__ . '/../partials/post_item.php'; ?>
        <?php endforeach; ?>
      <?php else: ?>
        <p>У вас пока нет событий.</p>
      <?php endif; ?>

    </div>
  <?php else: ?>
    <div class="user-info">
      <p>
        <strong>Подписчики:</strong> <?php echo (int)$subscriberCount; ?>
        <button onclick="toggleSubscribe(<?php echo (int)$profileUserId; ?>)" id="subscribe-btn">Подписаться</button>
      </p>

      <p><strong>Страна:</strong> <?php echo htmlspecialchars($userProfile['country_code'] ?? ''); ?>,
         <strong>Город:</strong> <?php echo htmlspecialchars($userProfile['city'] ?? ''); ?></p>

      <p><strong>Статус:</strong> <?php echo htmlspecialchars($userProfile['status'] ?? ''); ?></p>

      <?php
        $bio = $userProfile['bio'] ?? '';
        $bioShort = mb_substr($bio, 0, 200);
        $bioIsLong = (mb_strlen($bio) > 200);
      ?>
      <p id="bio-short">
        <?php echo nl2br(htmlspecialchars($bioShort)); ?>
        <?php if ($bioIsLong): ?>
          ... <a href="#" id="bio-read-more" onclick="showFullBio(event)">раскрыть</a>
        <?php endif; ?>
      </p>
      <?php if ($bioIsLong): ?>
        <p id="bio-full" style="display: none;">
          <?php echo nl2br(htmlspecialchars($bio)); ?>
          <a href="#" onclick="hideFullBio(event)">свернуть</a>
        </p>
      <?php endif; ?>

      <p><strong>Средний цвет событий:</strong> <span style="color: <?php echo htmlspecialchars($eventColor); ?>;"><?php echo $eventColor; ?></span></p>

      <h2>События пользователя</h2>
      <?php if (!empty($userPosts)): ?>
        <?php foreach ($userPosts as $post): ?>
          <?php include __DIR__ . '/../partials/post_item.php'; ?>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Пользователь ещё не опубликовал ни одного события.</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
