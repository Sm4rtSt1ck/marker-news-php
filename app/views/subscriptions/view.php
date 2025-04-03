<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Подписки</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/subscriptions.css">
  <script src="/assets/js/subscriptions/subscriptions.js"></script>
</head>
<body>
  <?php include __DIR__ . '/../templates/header.php'; ?>
  <div class="container subscriptions-page">
    <h1>Подписки</h1>

    <div class="subscriptions-tabs">
      <button class="tab-btn active" data-filter="all">Все</button>
      <button class="tab-btn" data-filter="user">Пользователи</button>
      <button class="tab-btn" data-filter="media">СМИ</button>
    </div>

    <div class="subscriptions-list">
      <?php if (!empty($subscriptions)): ?>
        <?php foreach ($subscriptions as $sub): ?>
          <?php 
            if (!empty($sub['user_id'])) {
                $type = 'user';
                if (isset($sub['show_realname']) && $sub['show_realname']) {
                    $displayName = htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']);
                } else {
                    $displayName = htmlspecialchars($sub['nickname']);
                }
                $link = "/user/view?user_id=" . $sub['user_id'];
                $avatar = !empty($sub['user_avatar']) ? $sub['user_avatar'] : '/assets/images/default_avatar.png';
            } elseif (!empty($sub['media_id'])) {
                $type = 'media';
                $displayName = htmlspecialchars($sub['media_name']);
                $link = "/media/view?media_id=" . $sub['media_id'];
                $avatar = !empty($sub['media_avatar']) ? $sub['media_avatar'] : '/assets/images/default_avatar.png';
            } else {
                $type = 'unknown';
                $displayName = "Неизвестно";
                $link = "#";
                $avatar = '/assets/images/default_avatar.png';
            }
          ?>
          <div class="subscription-item" data-type="<?php echo $type; ?>">
            <a href="<?php echo $link; ?>" class="subscription-link">
              <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Аватар" class="subscription-avatar">
              <span class="subscription-name"><?php echo $displayName; ?></span>
            </a>
            <button class="unsubscribe-btn" data-subscription-id="<?php echo $sub['subscription_id']; ?>">Отписаться</button>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>У вас пока нет подписок.</p>
      <?php endif; ?>
    </div>
  </div>
  <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
