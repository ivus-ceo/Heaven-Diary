<nav>
  <div class="header-navbar-container">
    <div class="header">
      <div class="nav-social-links">
        <a href="#">Соцсети:</a>
        <a href="https://vk.com/reflexp"><i class="fab fa-vk"></i></a>
        <a href="https://www.facebook.com/profile.php?id=100012104017674"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com/reflexp"><i class="fab fa-instagram"></i></a>
      </div>

      <div class="nav-login-signup">
        <a href="#">Достижения</a>
        <a href="#">Правила</a>
        <a href="#">О нас</a>
      </div>
    </div>
  </div>

  <div class="main-navbar-container">
    <div class="navbar">
      <div class="nav-search" onclick="OpenSearch(this)">
        <i class="fas fa-search"></i>
      </div>

      <div class="nav-links-left">
        <a href="#">Поиск</a>
        <a href="#">Достижения</a>
        <a href="#">Новости</a>
      </div>

      <div class="nav-logo">
        <a href="./">Heaven-Diary</a>
      </div>

      <div class="nav-links-right">
        <a href="profile">Профиль</a>
        <a href="#">Статистика</a>
        <a href="logout?logout=true">Выйти</a>
      </div>

      <div class="nav-bars" onclick="OpenSidebar(this)">
        <div></div>
        <div></div>
        <div></div>
      </div>

      <div class="nav-sidebar">
        <div class="sidebar-background">
          <div class="sidebar-times" onclick="CloseSidebar(this)">
            <i class="fal fa-times"></i>
          </div>

          <div class="sidebar-links">
            <a href="profile"><i class="fad fa-user"></i>Мой профиль</a>
            <a href="#"><i class="fad fa-star-half"></i>Достижения (WIP)</a>
            <a href="#"><i class="fad fa-comment-alt-check"></i>Статистика (WIP)</a>
            <a href="#"><i class="fad fa-newspaper"></i>Новости (WIP)</a>
            <a href="#"><i class="fad fa-shield-alt"></i>Правила (WIP)</a>
            <a href="#"><i class="fad fa-info-circle"></i>О нас (WIP)</a>
            <?php
              if (isset($_SESSION['id'])) {
                echo '<a href="logout?logout=true"><i class="fad fa-sign-out-alt"></i>Выйти</a>';
              } else {
                echo '<a href="./"><i class="fad fa-sign-in-alt"></i>Войти</a>';
              }
            ?>
          </div>
        </div>
      </div>

      <div class="nav-search-sidebar">
        <div class="sidebar-times" onclick="CloseSearch(this)">
          <i class="fal fa-times"></i>
        </div>

        <form action="search" method="POST">
          <input type="text" name="search-name" placeholder="Поиск" autocomplete="off" minlength="1" spellcheck="true">
          <button type="submit" name="button"><i class="fas fa-search"></i></button>
        </form>
      </div>
    </div>
  </div>
</nav>
