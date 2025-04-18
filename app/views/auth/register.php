<?php
$pageStyles = [
  '/assets/css/pages/auth/auth.css'
];
$pageTitle = "Регистрация";
include __DIR__ . '/../templates/header.php';
?>

  <div class="register-container">
    <h2>Регистрация</h2>

    <?php if (isset($errors) && count($errors) > 0): ?>
      <div class="error">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="/register" method="POST">
      <div>
        <label for="first_name">Имя:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
      </div>
      <div>
        <label for="last_name">Фамилия:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
      </div>
      <div>
        <label for="nickname">Никнейм (если оставить пустым – будет сгенерирован автоматически):</label>
        <input type="text" id="nickname" name="nickname" value="<?php echo htmlspecialchars($_POST['nickname'] ?? ''); ?>">
      </div>
      <div>
        <label for="email">Электронная почта:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
      </div>
      <div>
        <label for="phone">Номер телефона:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
      </div>
      <div>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div>
        <label for="birth_date">Дата рождения:</label>
        <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($_POST['birth_date'] ?? ''); ?>" required>
      </div>
      <div>
        <label for="country">Страна:</label>
        <select id="country" name="country" required>
          <option value="">Выберите страну</option>
          <?php if (isset($countries) && is_array($countries)): ?>
            <?php foreach ($countries as $country): ?>
              <option value="<?php echo htmlspecialchars($country['country_code']); ?>"
                <?php echo (isset($_POST['country']) && $_POST['country'] === $country['country_code']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($country['country_name']); ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
      <div>
        <label for="city">Город:</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>" required>
      </div>
      <div>
        <p>Как отображать имя:</p>
        <label>
          <input type="radio" name="display_name" value="full"
            <?php echo (!isset($_POST['display_name']) || $_POST['display_name'] === 'full') ? 'checked' : ''; ?>>
          Имя и Фамилия
        </label>
        <label>
          <input type="radio" name="display_name" value="nickname"
            <?php echo (isset($_POST['display_name']) && $_POST['display_name'] === 'nickname') ? 'checked' : ''; ?>>
          Никнейм
        </label>
      </div>
      <div>
        <button type="submit">Зарегистрироваться</button>
      </div>
    </form>
  </div>

  <?php include __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
