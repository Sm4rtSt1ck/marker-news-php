<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Лента постов</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/feed.css">
  <script src="/assets/js/feed.js"></script>
</head>
<body>
  <?php include __DIR__ . '/../templates/header.php'; ?>
  
  <div class="container feed-page">
    <h1>Лента постов</h1>

    <div class="feed-tabs">
      <a href="/feed?type=all" class="tab-link <?php echo ($type === 'all' ? 'active' : ''); ?>">Все посты</a>
      <a href="/feed?type=news" class="tab-link <?php echo ($type === 'news' ? 'active' : ''); ?>">Новости</a>
      <a href="/feed?type=events" class="tab-link <?php echo ($type === 'events' ? 'active' : ''); ?>">События</a>
    </div>

    <form action="/feed" method="GET" class="feed-filters">
      <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
      
      <div class="filter-group">
        <label for="country">Страна:</label>
        <select name="country" id="country">
          <option value="">Все</option>
          <?php if (!empty($countries)): ?>
            <?php foreach ($countries as $c): ?>
              <option value="<?php echo htmlspecialchars($c['country_code']); ?>" <?php echo ($country === $c['country_code'] ? 'selected' : ''); ?>>
                <?php echo htmlspecialchars($c['country_name']); ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
      
      <div class="filter-group">
        <label for="city">Город:</label>
        <input type="text" name="city" id="city" maxlength="50" value="<?php echo htmlspecialchars($city); ?>">
      </div>
      
      <div class="filter-group">
        <label for="date_from">Дата с:</label>
        <input type="date" name="date_from" id="date_from" value="<?php echo htmlspecialchars($date_from); ?>">
      </div>
      
      <div class="filter-group">
        <label for="date_to">Дата по:</label>
        <input type="date" name="date_to" id="date_to" value="<?php echo htmlspecialchars($date_to); ?>">
      </div>
      
      <div class="filter-group">
        <label for="category">Характер:</label>
        <select name="category" id="category">
          <option value="">Все</option>
          <?php
            $postCategories = ['good', 'bad', 'neutral', 'funny', 'sad', 'strange'];
            foreach ($postCategories as $cat):
          ?>
            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($category === $cat ? 'selected' : ''); ?>>
              <?php echo ucfirst($cat); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="filter-group">
        <label for="subscription">Подписки:</label>
        <select name="subscription" id="subscription">
          <option value="any" <?php echo ($subscription_filter === 'any' ? 'selected' : ''); ?>>Любые</option>
          <option value="subscriptions" <?php echo ($subscription_filter === 'subscriptions' ? 'selected' : ''); ?>>Подписки</option>
        </select>
      </div>
      
      <div class="filter-group">
        <label for="search_title">Поиск по названию:</label>
        <input type="text" name="search_title" id="search_title" value="<?php echo htmlspecialchars($search_title); ?>">
      </div>
      
      <div class="filter-group">
        <label for="search_location">Поиск по локации:</label>
        <input type="text" name="search_location" id="search_location" value="<?php echo htmlspecialchars($search_location); ?>">
      </div>
      
      <button type="submit">Применить фильтры</button>
    </form>

    <div class="feed-posts">
      <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
          <?php include __DIR__ . '/../partials/post_item.php'; ?>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Постов не найдено.</p>
      <?php endif; ?>
    </div>
  </div>

  <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
