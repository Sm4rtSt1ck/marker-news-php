<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Оставить жалобу на пост</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <script src="/assets/js/general/max_length.js"></script>
  <style>
    .form-section { margin-bottom: 20px; }
    .max-length-warning { color: red; font-size: 0.9em; margin-top: 5px; }
  </style>
</head>
<body>
  <?php include __DIR__ . '/../templates/header.php'; ?>
  
  <div class="container">
    <h1>Оставить жалобу на пост</h1>
    <?php
      if (!empty($errors)) {
          echo '<div class="error"><ul>';
          foreach ($errors as $err) {
              echo '<li>' . htmlspecialchars($err) . '</li>';
          }
          echo '</ul></div>';
      }
      if (isset($_SESSION['success'])) {
          echo '<div class="success">' . htmlspecialchars($_SESSION['success']) . '</div>';
          unset($_SESSION['success']);
      }
    ?>
    <form action="/report/create" method="POST">
      <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
      
      <div class="form-section">
        <label for="category">Категория жалобы:</label>
        <select name="category" id="category" required>
          <option value="">Выберите категорию</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat); ?>"
              <?php echo (isset($_POST['category']) && $_POST['category'] === $cat) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $cat))); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="form-section">
        <label for="description">Описание:</label>
        <textarea id="description" name="description" data-maxlength="10000" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
      </div>
      
      <button type="submit">Отправить жалобу</button>
    </form>
  </div>
  
  <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
