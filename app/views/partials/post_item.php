<!-- app/views/partials/post_item.php -->
<div class="user-post-item">
  <h3><a href="/post/view?post_id=<?php echo htmlspecialchars($post['post_id']); ?>">
    <?php echo htmlspecialchars($post['title']); ?>
  </a></h3>
  <p><strong>Характер:</strong> <?php echo htmlspecialchars($post['category']); ?></p>
  <?php if (!empty($post['latitude']) && !empty($post['longitude'])): ?>
    <p>Координаты: <?php echo $post['latitude']; ?>, <?php echo $post['longitude']; ?></p>
  <?php endif; ?>
  <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
</div>
