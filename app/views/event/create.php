<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Создание события</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    .form-section { margin-bottom: 20px; }
    .max-length-warning { color: red; font-size: 0.9em; margin-top: 5px; }
    .map-container { width: 100%; height: 400px; border: 1px solid #ccc; margin-top: 10px; display: none; }
  </style>
  <script src="/assets/js/general/max_length.js"></script>
</head>
<body>
  <?php include __DIR__ . '/../templates/header.php'; ?>

  <div class="container">
    <h1>Создание события</h1>
    <?php if (isset($error)): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
      <div class="success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form action="/event/create" method="POST" enctype="multipart/form-data">
      <!-- Заголовок -->
      <div class="form-section">
        <label for="title">Заголовок:</label>
        <input type="text" id="title" name="title" maxlength="255" data-maxlength="255" required>
        <div id="title-warning" class="max-length-warning" style="display: none;"></div>
      </div>

      <!-- Метка на карте -->
      <div class="form-section">
        <label for="map_marker">Метка на карте:</label>
        <button type="button" id="open-map-btn">Выбрать на карте</button>
        <input type="text" id="address" name="address" placeholder="Или введите адрес события">
        <!-- Скрытые поля для координат -->
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
        <div id="map" class="map-container"></div>
      </div>

      <!-- Характер события -->
      <div class="form-section">
        <label for="category">Характер события:</label>
        <select id="category" name="category" required>
          <option value="">Выберите характер</option>
          <option value="good">Хороший</option>
          <option value="bad">Плохой</option>
          <option value="neutral">Нейтральный</option>
          <option value="funny">Веселый</option>
          <option value="sad">Грустный</option>
          <option value="strange">Странный</option>
        </select>
      </div>

      <!-- Описание -->
      <div class="form-section">
        <label for="description">Описание:</label>
        <textarea id="description" name="description" maxlength="10000" data-maxlength="10000" required></textarea>
        <div id="description-warning" class="max-length-warning" style="display: none;"></div>
      </div>

      <!-- Вложения -->
      <div class="form-section">
        <label for="attachments">Вложения (не более 10 штук):</label>
        <input type="file" id="attachments" name="attachments[]" multiple accept="image/*,video/*,audio/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
        <div id="attachments-warning" class="max-length-warning" style="display: none;"></div>
      </div>

      <button type="submit">Создать событие</button>
    </form>
  </div>

  <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
