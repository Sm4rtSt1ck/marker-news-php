<!-- app/views/post/view.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($post['title']); ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/post.css">
  <script src="/assets/js/post/post.js"></script>
</head>
<body>
  <?php include __DIR__ . '/../templates/header.php'; ?>
  
  <div class="container post-container">
    <div class="post-author">
      <?php if ($post['user_id'] !== null):
              if (isset($author['show_realname']) && $author['show_realname']) {
                  $displayName = htmlspecialchars($author['first_name'] . ' ' . $author['last_name']);
              } else {
                  $displayName = htmlspecialchars($author['nickname']);
              }
            ?>
        <img src="<?php echo htmlspecialchars($author['avatar_url'] ?? '/assets/images/default_avatar.png'); ?>" alt="Аватар" class="avatar">
        <span class="author-name"><?php echo $displayName; ?></span>
      <?php elseif ($post['media_id'] !== null):
              $displayName = htmlspecialchars($media['name']);
            ?>
        <img src="<?php echo htmlspecialchars($media['avatar_url'] ?? '/assets/images/default_media.png'); ?>" alt="Аватар СМИ" class="avatar">
        <span class="author-name"><?php echo $displayName; ?></span>
      <?php endif; ?>
    </div>

    <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>

    <div class="post-category">
      <strong>Характер:</strong> <?php echo htmlspecialchars($post['category']); ?>
    </div>

    <div class="post-map">
      <?php if (!empty($post['latitude']) && !empty($post['longitude'])): ?>
        <span>Координаты: <?php echo htmlspecialchars($post['latitude']); ?>, <?php echo htmlspecialchars($post['longitude']); ?></span>
      <?php elseif (!empty($post['address'])): ?>
        <span>Адрес: <?php echo htmlspecialchars($post['address']); ?></span>
      <?php endif; ?>
    </div>

    <div class="post-description">
      <?php echo nl2br(htmlspecialchars($post['description'])); ?>
    </div>

    <?php if (!empty($attachments)): ?>
      <div class="post-attachments">
        <?php foreach ($attachments as $att): ?>
          <?php if ($att['file_type'] === 'image'): ?>
            <img src="<?php echo htmlspecialchars($att['file_url']); ?>" alt="Вложение" class="attachment-image">
          <?php else: ?>
            <a href="<?php echo htmlspecialchars($att['file_url']); ?>" target="_blank">Скачать файл</a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="post-reactions">
      <button id="reaction-button" style="background-color: <?php echo htmlspecialchars($reaction_color); ?>;" data-post-id="<?php echo htmlspecialchars($post['post_id']); ?>">
        Реагировать
      </button>
      <div id="reaction-options" class="reaction-options" style="display: none;">
        <?php if (!empty($reactionVariants)): ?>
          <?php foreach ($reactionVariants as $variant): ?>
            <button class="reaction-option" data-reaction="<?php echo htmlspecialchars($variant); ?>" data-post-id="<?php echo htmlspecialchars($post['post_id']); ?>">
              <?php echo htmlspecialchars(ucfirst($variant)); ?>
            </button>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="post-comment-form">
      <textarea id="comment-text" placeholder="Ваш комментарий (не более 5000 символов)" maxlength="5000" data-maxlength="5000"></textarea>
      <div id="comment-warning" class="max-length-warning" style="display: none;"></div>
      <button id="submit-comment" disabled data-post-id="<?php echo htmlspecialchars($post['post_id']); ?>">Опубликовать</button>
    </div>

    <div id="comments-list" class="post-comments">
      <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
          <div class="comment" data-comment-id="<?php echo htmlspecialchars($comment['comment_id']); ?>">
            <div class="comment-author">
              <img src="<?php echo htmlspecialchars($comment['avatar_url'] ?? '/assets/images/default_avatar.png'); ?>" alt="Аватар" class="avatar-small">
              <span class="comment-author-name"><?php echo htmlspecialchars($comment['author_name']); ?></span>
            </div>
            <div class="comment-text">
              <?php echo nl2br(htmlspecialchars($comment['text'])); ?>
            </div>
            <div class="comment-date">
              <?php echo htmlspecialchars($comment['created_at']); ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Нет комментариев.</p>
      <?php endif; ?>
    </div>

    <div class="post-report">
      <button id="report-button" data-post-id="<?php echo htmlspecialchars($post['post_id']); ?>">Пожаловаться</button>
    </div>
  </div>

  <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
