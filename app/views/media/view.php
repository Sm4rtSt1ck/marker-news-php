<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($media['name']); ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/media_view.css">
  <script src="/assets/js/media/view.js"></script>
  <script src="/assets/js/subscriptions/subscribe.js"></script>
</head>
<body>
  <?php include __DIR__ . '/../templates/header.php'; ?>

  <div class="container media-page">
    <div class="media-cover">
      <img src="<?php echo htmlspecialchars($media['cover_url'] ?? '/assets/images/default_cover.jpg'); ?>" alt="Шапка СМИ">
    </div>
    <div class="media-avatar">
      <img src="<?php echo htmlspecialchars($media['avatar_url'] ?? '/assets/images/default_avatar.png'); ?>" alt="Аватар СМИ">
    </div>
    <h1 class="media-name"><?php echo htmlspecialchars($media['name']); ?></h1>

    <div class="media-subscription">
      <span>Подписчики: <?php echo $subscriberCount; ?></span>
      <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] != $media['owner_id']): ?>
        <button id="subscribe-btn" data-media-id="<?php echo htmlspecialchars($media['media_id']); ?>" data-subscription-id="<?php echo $subscriptionId; ?>">
          <?php echo $isSubscribedMedia ? 'Отписаться' : 'Подписаться'; ?>
        </button>
      <?php endif; ?>
    </div>

    <?php
      $description = $media['description'] ?? '';
      $shortDesc = mb_substr($description, 0, 200);
      $descIsLong = mb_strlen($description) > 200;
    ?>
    <div class="media-description">
      <p id="desc-short"><?php echo nl2br(htmlspecialchars($shortDesc)); ?>
        <?php if ($descIsLong): ?>
          ... <a href="#" id="desc-read-more" onclick="showFullDescription(event)">раскрыть</a>
        <?php endif; ?>
      </p>
      <?php if ($descIsLong): ?>
        <p id="desc-full" style="display:none;"><?php echo nl2br(htmlspecialchars($description)); ?> <a href="#" onclick="hideFullDescription(event)">свернуть</a></p>
      <?php endif; ?>
    </div>

    <div class="media-news-color">
      <strong>Средний цвет новостей:</strong> <span style="color: <?php echo htmlspecialchars($avgColor); ?>;"><?php echo htmlspecialchars($avgColor); ?></span>
    </div>

    <?php if ($isOwnerOrModerator): ?>
      <div class="media-settings-link">
        <a href="/media/settings?media_id=<?php echo htmlspecialchars($media['media_id']); ?>">Настройки СМИ</a>
      </div>
    <?php endif; ?>

    <div class="media-news">
      <h2>Новости СМИ</h2>
      <?php if (!empty($newsPosts)): ?>
        <?php foreach ($newsPosts as $post): ?>
          <?php include __DIR__ . '/../partials/post_item.php'; ?>
        <?php endforeach; ?>
      <?php else: ?>
        <p>СМИ пока не опубликовало новости.</p>
      <?php endif; ?>
    </div>
    
  </div>

  <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
