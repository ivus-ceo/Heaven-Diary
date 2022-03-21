<?php
  require 'php/db.php';
  session_start();

  if (isset($_SESSION['id'])) {
    header('Location: profile');
  }
?>

<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <?php include 'includes/head.php'; ?>
    <title>Heaven-Diary | Добро пожаловать</title>
  </head>
  <body>
    <main>
      <div class="main-welcome-container">
        <div class="welcome-background-container">
          <div class="welcome-caption">
            <?php
              $seed = floor(time() / (10));
              srand($seed);
              $phrase = rand(0,5);

              $phrases = array(
                "1",
                "2",
                "3",
                "4",
                "5",
                "6"
              );
            ?>

            <h1>Добро пожаловать! Рады снова видеть вас на Heaven-Diary!</h1>
          </div>

          <div class="welcome-forms">
            <div class="forms-options">
              <div class="active" title="Войти" onclick="OpenTab(1)">Авторизация</div>
              <div title="Зарегистрироваться" onclick="OpenTab(2)">Регистрация</div>
            </div>

            <div class="login-form active">
              <form action="php/hd-login-user.php" method="POST">
                <input type="text" name="login-email" placeholder="Логин / E-Mail">
                <input type="password" name="password" minlength="3" placeholder="Пароль">
                <a href="#">Забыли пароль?</a>
                <button type="submit" name="login-button">Войти</button>

                <?php if (!empty($_GET['error'])): ?>
                  <div class="message" style="margin-bottom: 10px;">
                  <?php
                    if (!empty($_GET['error'])) {
                      echo '<i class="fas fa-info-circle"></i> ';
                      switch ($_GET['error']) {
                        case 'empty-fields':
                          echo "Некоторые поля остались пустыми";
                          break;
                        case 'sql-error':
                          echo "Ошибка базы данных";
                          break;
                        case 'wrong-password':
                          echo "Неверный пароль";
                          break;
                        case 'account-inactive':
                          echo "Аккаунт не активирован";
                          break;
                        case 'username-not-found':
                          echo "Имя пользователя не найдено";
                          break;
                        default:
                          echo "Неизвестная ошибка";
                          break;
                      }
                    };
                  ?>
                </div>
                <?php endif; ?>
                <p>До сих пор нет аккаунта? <a href="#" onclick="OpenTab(2)"><strong>Зарегистрируйся сейчас!</strong></a> Это очень просто и затем наслаждайся всеми привилегиями!</p>
              </form>
            </div>

            <div class="signup-form">
              <form action="php/hd-new-user.php" method="POST">
                <input type="text" name="name" placeholder="Имя" required>
                <input type="text" name="last" placeholder="Фамилия" required>
                <input type="text" name="email" placeholder="E-Mail" required>
                <input type="text" name="login" placeholder="Логин" required>
                <input type="password" name="password" minlength="6" placeholder="Пароль" required>
                <select name="sex">
                  <option value="male">Мужчина</option>
                  <option value="female">Женщина</option>
                </select>
                <button class="signup-btn" type="submit" name="signup-button">Завершить регистрацию!</button>

                <?php if (!empty($_GET['error'])): ?>
                  <div class="message" style="margin-bottom: 10px;">
                  <?php
                    if (!empty($_GET['error'])) {
                      echo '<i class="fas fa-info-circle"></i> ';
                      switch ($_GET['error']) {
                        case 'empty-fields':
                          echo "Некоторые поля остались пустыми";
                          break;
                        case 'sql-error':
                          echo "Ошибка базы данных";
                          break;
                        case 'invalid-login-email':
                          echo "Неверная почта и имя пользователя";
                          break;
                        case 'invalid-email':
                          echo "Неверная почта";
                          break;
                        case 'invalid-login':
                          echo "Неверный логин";
                          break;
                        case 'username-or-email-taken':
                          echo "Имя пользователя или почта уже заняты";
                          break;
                        case 'wrong-password':
                          echo "Неверный пароль";
                          break;
                        default:
                          echo "Неизвестная ошибка";
                          break;
                      }
                    } elseif (!empty($_GET['signup'])) {
                      echo "Регистрация успешна!";
                    };
                  ?>
                </div>
                <?php endif; ?>
                <p>Пройдите простую регистрацию, она не займет больше пяти минут! Есть проблемы? <a href="#"><strong>Обратитесь в поддержку!</strong></a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    <header>
      <div class="main-slider-container">
        <div class="slide active" style="background-image: url(https://images.unsplash.com/photo-1519373344801-14c1f9539c9c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80)">

        </div>

        <div class="slide" style="background-image: url(media/images/007-space.jpg)">

        </div>

        <div class="slide" style="background-image: url(media/images/004-crowd.jpg)">

        </div>

        <div class="slide" style="background-image: url(media/images/008-nature.jpg)">

        </div>

        <div class="slider-options">

        </div>
      </div>
    </header>

    <header>
      <div class="main-slider">
        <div class="content">
          <div class="slide" id="slide-1" style="background-image: url(media/images/005-nature.jpg)"></div>
          <div class="slide" id="slide-2" style="background-image: url(media/images/007-space.jpg)"></div>
          <div class="slide" id="slide-3" style="background-image: url(media/images/004-crowd.jpg)"></div>
          <div class="slide" id="slide-4" style="background-image: url(media/images/008-nature.jpg)"></div>
        </div>

        <div class="options">
          <a href="#slide-1"></a>
          <a href="#slide-2"></a>
          <a href="#slide-3"></a>
          <a href="#slide-4"></a>
        </div>
      </div>
    </header>

    <script src="js/main.js"></script>
  </body>
</html>
