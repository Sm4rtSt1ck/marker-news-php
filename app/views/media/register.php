<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Регистрация СМИ</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <script src="/assets/js/max_length.js"></script>
</head>
<body>
  <?php include __DIR__ . '/../templates/header.php'; ?>

  <div class="container">
    <h1>Регистрация СМИ</h1>
    <?php
      if (!empty($errors)) {
          echo '<div class="error"><ul>';
          foreach ($errors as $error) {
              echo '<li>' . htmlspecialchars($error) . '</li>';
          }
          echo '</ul></div>';
      }
      if (isset($_SESSION['success'])) {
          echo '<div class="success">' . htmlspecialchars($_SESSION['success']) . '</div>';
          unset($_SESSION['success']);
      }
    ?>
    
    <form action="/media/register" method="POST">
      <div class="form-section">
        <label for="name">Наименование СМИ:</label>
        <input type="text" id="name" name="name" maxlength="100" data-maxlength="100" required>
      </div>
      <div class="form-section">
        <label for="news_type">Тип новостей:</label>
        <select id="news_type" name="news_type" required>
          <option value="">Выберите тип новостей</option>
          <option value="Entertainment">Развлекательные</option>
          <option value="Political">Политические</option>
          <option value="Economic">Экономические</option>
          <option value="Sports">Спортивные</option>
          <option value="Technology">Технологические</option>
          <option value="Cultural">Культурные</option>
          <option value="Scientific">Научные</option>
          <option value="Social">Социальные</option>
          <option value="Other">Другое</option>
        </select>
      </div>
      <div class="form-section">
        <label for="description">Описание организации:</label>
        <textarea id="description" name="description" maxlength="10000" data-maxlength="10000" required></textarea>
      </div>
      <div class="form-section">
        <label for="email">Электронная почта:</label>
        <input type="email" id="email" name="email" maxlength="100" data-maxlength="100" required>
      </div>
      <div class="form-section">
        <label for="phone">Номер телефона:</label>
        <input type="text" id="phone" name="phone" maxlength="20" data-maxlength="20" required>
      </div>
      <button type="submit">Зарегистрировать СМИ</button>
    </form>
  </div>

  <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
