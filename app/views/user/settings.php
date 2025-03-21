<!-- app/views/user/settings.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Настройки пользователя</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    .settings-section { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; }
    .settings-section h2 { margin-top: 0; }
    .max-length-warning { color: red; font-size: 0.9em; margin-top: 5px; }
    .avatar { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
    .cover { width: 100%; max-height: 200px; object-fit: cover; }
    .hidden { display: none; }
  </style>
  <!-- Подключаем скрипт проверки длины полей -->
  <script src="/assets/js/general/max_length.js"></script>
  <script>
    // Функции редактирования и отмены редактирования для персональных данных и профиля
    function enableEditing(sectionId) {
      var inputs = document.querySelectorAll('#' + sectionId + ' input, #' + sectionId + ' select, #' + sectionId + ' textarea');
      inputs.forEach(function(input) {
        input.disabled = false;
      });
      document.getElementById(sectionId + '-edit-btn').classList.add('hidden');
      document.getElementById(sectionId + '-save-btn').classList.remove('hidden');
      document.getElementById(sectionId + '-cancel-btn').classList.remove('hidden');
    }

    function cancelEditing(sectionId) {
      location.reload();
    }
  </script>
</head>
<body>
  <?php include __DIR__ . '/../templates/header.php'; ?>

  <div class="container">
    <h1>Настройки пользователя</h1>
    <?php
      if (isset($user['errors'])) {
        echo '<div class="error"><ul>';
        foreach ($user['errors'] as $error) {
          echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul></div>';
      }
      if (isset($_SESSION['success'])) {
        echo '<div class="success">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']);
      }
    ?>

    <!-- Personal info form -->
    <form action="/settings" method="POST">
      <input type="hidden" name="update_personal_data" value="1">
      <div id="personal-data" class="settings-section">
        <h2>Персональные данные</h2>
        <div>
          <label for="first_name">Имя:</label>
          <input type="text" id="first_name" name="first_name" maxlength="50" data-maxlength="50"
                 value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" disabled required>
        </div>
        <div>
          <label for="last_name">Фамилия:</label>
          <input type="text" id="last_name" name="last_name" maxlength="50" data-maxlength="50"
                 value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" disabled required>
        </div>
        <div>
          <label for="nickname">Никнейм:</label>
          <input type="text" id="nickname" name="nickname" maxlength="20" data-maxlength="20"
                 value="<?php echo htmlspecialchars($user['nickname'] ?? ''); ?>" disabled required>
        </div>
        <div>
          <label for="email">Почта:</label>
          <input type="email" id="email" name="email" maxlength="100" data-maxlength="100"
                 value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled required>
        </div>
        <div>
          <label for="phone">Телефон:</label>
          <input type="text" id="phone" name="phone" maxlength="20" data-maxlength="20"
                 value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" disabled required>
        </div>
        <div>
          <label for="birth_date">Дата рождения:</label>
          <input type="date" id="birth_date" name="birth_date"
                 value="<?php echo htmlspecialchars($user['birth_date'] ?? ''); ?>" disabled required>
        </div>
        <div>
          <label>Отображать имя и фамилию:</label>
          <input type="radio" name="display_name" value="full"
            <?php echo ((isset($user['show_realname']) && $user['show_realname']) || !isset($user['show_realname'])) ? 'checked' : ''; ?> disabled> Да
          <input type="radio" name="display_name" value="nickname"
            <?php echo ((isset($user['show_realname']) && !$user['show_realname'])) ? 'checked' : ''; ?> disabled> Нет
        </div>
        <div>
          <label for="country">Страна:</label>
          <select id="country" name="country" disabled required>
            <option value="">Выберите страну</option>
            <?php if (isset($countries) && is_array($countries)): ?>
              <?php foreach ($countries as $country): ?>
                <option value="<?php echo htmlspecialchars($country['country_code']); ?>"
                  <?php echo (isset($user['country_code']) && $user['country_code'] === $country['country_code']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($country['country_name']); ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div>
          <label for="city">Город:</label>
          <input type="text" id="city" name="city" maxlength="50" data-maxlength="50"
                 value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" disabled required>
        </div>
        <div>
          <label for="passport">Паспорт (серия и номер):</label>
          <input type="text" id="passport" name="passport"
                 value="<?php echo htmlspecialchars($user['passport'] ?? ''); ?>" disabled>
        </div>
        <div>
          <button type="button" id="personal-data-edit-btn" onclick="enableEditing('personal-data')">Редактировать</button>
          <button type="submit" id="personal-data-save-btn" class="hidden">Сохранить</button>
          <button type="button" id="personal-data-cancel-btn" class="hidden" onclick="cancelEditing('personal-data')">Отменить</button>
        </div>
      </div>
    </form>

    <!-- Profile form -->
    <form action="/settings" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="update_profile" value="1">
      <div id="profile" class="settings-section">
        <h2>Профиль</h2>
        <div>
          <label>Аватар:</label>
          <img src="<?php echo htmlspecialchars($user['avatar_url'] ?? '/assets/images/default_avatar.png'); ?>" alt="Аватар" class="avatar">
          <input type="file" name="avatar" accept="image/*">
        </div>
        <div>
          <label>Шапка:</label>
          <img src="<?php echo htmlspecialchars($user['cover_url'] ?? '/assets/images/default_cover.jpg'); ?>" alt="Шапка" class="cover">
          <input type="file" name="cover" accept="image/*">
        </div>
        <div>
          <label for="status">Статус:</label>
          <input type="text" id="status" name="status"
                 value="<?php echo htmlspecialchars($user['status'] ?? ''); ?>" disabled>
        </div>
        <div>
          <label for="bio">Описание:</label>
          <textarea id="bio" name="bio" disabled><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
        </div>
        <div>
          <button type="button" id="profile-edit-btn" onclick="enableEditing('profile')">Редактировать</button>
          <button type="submit" id="profile-save-btn" class="hidden">Сохранить</button>
          <button type="button" id="profile-cancel-btn" class="hidden" onclick="cancelEditing('profile')">Отменить</button>
        </div>
      </div>
    </form>

    <!-- Password form -->
    <form action="/settings" method="POST">
      <input type="hidden" name="change_password" value="1">
      <div id="security" class="settings-section">
        <h2>Безопасность</h2>
        <div>
          <label for="old_password">Старый пароль:</label>
          <input type="password" id="old_password" name="old_password" minlength="8" maxlength="30" data-maxlength="30">
        </div>
        <div>
          <label for="new_password">Новый пароль:</label>
          <input type="password" id="new_password" name="new_password" minlength="8" maxlength="30" data-maxlength="30">
        </div>
        <div>
          <label for="confirm_new_password">Повтор нового пароля:</label>
          <input type="password" id="confirm_new_password" name="confirm_new_password" minlength="8" maxlength="30" data-maxlength="30">
        </div>
        <div>
          <button type="submit">Изменить пароль</button>
        </div>
      </div>
    </form>

  </div>

  <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
