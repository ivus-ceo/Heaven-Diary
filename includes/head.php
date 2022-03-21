<!-- МЕТА -->
<meta charset="UTF-8"> <!-- Устанавливает кодировку -->
<meta name="ROBOTS" content="NONE"> <!-- Индексирование страниц или использования ссылок ботам -->
<meta name="DESCRIPTION" content=""> <!-- Один из самых важных при раскрутке страницы (Описание страницы) -->
<meta name="KEYWORDS" content="Тест, Пройти тест, Курсы, Бесплатные тесты"> <!-- Ключевые слова не учитываются Google и Rambler -->
<meta name="AUTHOR" content="Ivan Uskov"> <!-- Указания автора или фирмы, www.business.com -->
<meta name="VIEWPORT" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"> <!-- Сайт будет красивым на всех девайсах -->
<!-- Иконка сайта -->
<link rel="shortcut icon" href="media/icons/favicon.ico">
<!-- Стили -->
<link rel="stylesheet" href="scss/style.min.css">
<link rel="stylesheet" href="media/icons/all.min.css">
<!-- Скрипты -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script src="js/jquery.inview.min.js"></script>

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
  const editor = document.querySelector('.editor');

  function doRichEditCommand(command) {
    document.execCommand(command, false, null);
  }

  function doRichEditCommandWithArg(command, arg) {
    document.execCommand(command, false, arg);
  }
</script>
