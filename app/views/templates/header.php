<header>
  <div class="container">
    <h1><a href="/">Marker News</a></h1>
    <nav>
      <ul>
        <li><a href="/">Главная</a></li>
        <?php if (isset($_SESSION['user'])): ?>
          <li><a href="/profile">Профиль</a></li>
          <li><a href="/settings">Настройки</a></li>
          <li><a href="/logout">Выход</a></li>
        <?php else: ?>
          <li><a href="/login">Вход</a></li>
          <li><a href="/register">Регистрация</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>
