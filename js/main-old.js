function scrollWin(Y, X) {
  window.scroll({ top: Y, left: X, behavior: 'smooth' });
}

/*-_-_-_-_- Play Sound -_-_-_-_-*/
$(document).ready(function() {
  var obj = document.createElement("audio");
  obj.src = "media/sound/notification.mp3";
  obj.volume = 1;
  obj.autoPlay = false;
  obj.preLoad = true;
  obj.controls = true;

  $(".playSound").click(function() {
    obj.play();
  });
});

$(document).click(function() {
  var width1 = $(window).width();
  if (width1 >= 1024) {
    $('.article-dropdown').fadeOut(300);
  }
});

$('#image').click(function() {
  if ($(this).css('max-height') == '100%' && $(this).css('cursor') == 'zoom-in') {
    $(this).css({
      'max-height': '300%',
      'cursor': 'zoom-out'
    });
  } else {
    $(this).css({
      'max-height': '100%',
      'cursor': 'zoom-in'
    });
  }
});

function modal(element) {
  var src = $(element).attr('src');
  $('#image').attr('src', src);
  $('.modal-container').css('display', 'flex');
  $('#image').css('display', 'block');

  $('#image').mouseleave(function() {
    $(this).parent().fadeOut();
    $(this).css({
      'max-height': '100%',
      'cursor': 'zoom-in'
    });
  });
}

function toggle_visible(element) {
  if ($(element).html() == '<i class="far fa-eye"></i>') {
    $(element).html('<i class="fas fa-low-vision"></i>');
    $(element).attr('title', 'Открыто для подписчиков');
    $("input[name=visible]").attr('value', '1');
  } else if ($(element).html() == '<i class="fas fa-low-vision"></i>') {
    $(element).html('<i class="fas fa-eye-slash"></i>');
    $(element).attr('title', 'Закрыто');
    $("input[name=visible]").attr('value', '0');
  } else {
    $(element).html('<i class="far fa-eye"></i>');
    $(element).attr('title', 'Открыто');
    $("input[name=visible]").attr('value', '2');
  }
}

function create_large_article() {
  var large_article = $('.large-article-data').html();
  $('#editor').attr('contenteditable', 'false');
  $('#editor').html(large_article);
  $("input[name=type]").attr('value', '2');
}

function remove_large_article_creation() {
  $('#editor .large-article-form').remove();
  $('#editor').attr('contenteditable', 'true');
  $("input[name=type]").attr('value', '1');
  $('#article-add').attr('onsubmit', 'ajax_add_article(event)');
}

function create_survey_article() {
  var survey_article = $('.survey-article-data').html();
  $('#editor').attr('contenteditable', 'false');
  $('#editor').html(survey_article);
  $("input[name=type]").attr('value', '3');
}

function remove_survey_article_creation() {
  $('#editor .survey-article-form').remove();
  $('#editor').attr('contenteditable', 'true');
  $("input[name=type]").attr('value', '1');
  $('#article-add').attr('onsubmit', 'ajax_add_article(event)');
}

function survey_option_append(element) {
  var option = $('.survey-article-form div:nth-last-child(2)').find('input').attr('name');
  if (option == "NaN" || option == "survey_title") {
    option = 0;
  }
  option++;
  $(element).before('<div class="option-remove"><input type="text" name="' + option + '" placeholder="Ответ . . ." required autocomplete="off"><i class="fas" onclick="survey_option_remove(this)"></i></div>');
}

function survey_option_remove(element) {
  $(element).parent().remove();
}

function openNotificationTab() {
  if ($('.notification-container').css('display') == 'none') {
    $('.notification-container').fadeIn('500');
    $('.nav-notification').css({
      'background': '#4e526b',
      'color': '#ff5e3a'
    });
  } else {
    $('.notification-container').fadeOut('500');
    $('.nav-notification').css({
      'background': 'transparent',
      'color': '#BEC1D6'
    });
  }
}

function openSearchTab() {
  if ($('.search-container').css('height') == '0px') {
    $('.nav-search').html('<i class="fas fa-times"></i>');
    $('.search-container').css('height', '70px');
  } else {
    $('.nav-search').html('<i class="fas fa-search"></i>');
    $('.search-container').css('height', '0px');
  }
}

function openLeftTab() {
  if ($('.left-column').css('left') == '0px') {
    $('.left-column').css('left', '-260px');
  } else {
    $('.left-column').css('left', '0px');
  }
}

function openRightTab() {
  if ($('.right-column').css('right') == '0px') {

    $('.nav-bars').html('<i class="fas fa-bars"></i>');
    $('.right-column').css('right', '-260px');
  } else {
    $('.nav-bars').html('<i class="fas fa-times"></i>');
    $('.right-column').css('right', '0px');
  }
}

function openDropdown(element) {
  $(element).next().fadeIn(300);
}

function hideDropdown(element) {
  var width = $(window).width();
  if (width <= 1024) {
    $('.article-dropdown').fadeOut(300);
  }
}

function ajax_add_article(event) {
  event.preventDefault();
  var data = $('#article-add').serialize();

  if ($('#editor .large-article-form').length > 0) {
    $('#editor .large-article-form .editor').find('img').attr({
      class: 'hd-image',
      onclick: 'modal(this)'
    });
    var article_content = $('#editor').find('.editor').html();
    var article_image = $('#editor').find("input[name=article-image]").val();
  } else if ($('#editor .survey-article-form').length > 0) {

    var article_content = $('.option-remove input').serialize();

  } else {
    $('#editor').children().removeAttr('style');
    $('#editor').find('img').attr({
      class: 'hd-image',
      onclick: 'modal(this)'
    });
    var article_content = $('#editor').html();
  }

  $('#editor').html('');
  $("input[name=type]").attr('value', '1');
  $('#editor').attr('contenteditable', 'true');

  $('.articles-start').load('ajax/ajax-articles-new.php', {
    article_content: article_content,
    article_image: article_image,
    data: data
  });
}

function ajax_remove_article(article_id) {
  $('.articles-start').load("ajax/ajax-remove-article.php", {
    article_id: article_id
  });
}

function ajax_article_archive(article_id, element) {
  if ($(element).html() == 'Архивировать') {
    $(element).html('Убрать из архива');
  } else {
    $(element).html('Архивировать');
  }
  $('#editor').load('ajax/ajax-article-archive.php', {
    article_id: article_id
  });
}

function ajax_edit_article(article_id, element) {
  $(element).closest('.small-article').find('.small-article-content').attr("contenteditable", "true");
  $(element).closest('.small-article').find('.small-article-content img').attr({
    class: 'hd-image',
    onclick: 'null'
  });
  $(element).closest('.small-article').find('footer').load('ajax/ajax-save-button.php', {
    article_id: article_id
  });
}

function ajax_edit_large_article(article_id, element) {
  $('#editor').attr('contenteditable', 'false');
  $('#article-add').attr('onsubmit', 'ajax_save_edited_large_article(event, ' + article_id + ', this)');
  $('#editor').load('ajax/ajax-edit-large-article.php', {
    article_id: article_id
  });
  scrollWin(500, 0);
}

function ajax_save_edited_large_article(event, article_id, element) {
  event.preventDefault();
  var article_title = $('#editor').find('input[name=article_title]').val();
  var article_image = $('#editor').find("input[name=article-image]").val();
  var article_visible = $("input[name=visible]").val();
  var article_content = $('#editor').find('.editor').html();
  $('#editor .large-article-form .editor').find('img').attr({
    class: 'hd-image',
    onclick: 'modal(this)'
  });

  $('#article-add').attr('onsubmit', 'ajax_add_article(event)');
  $('#editor .large-article-form').remove();
  $('#editor').attr('contenteditable', 'true');

  $('.articles-start').load('ajax/ajax-save-edited-article.php', {
    article_id: article_id,
    article_title: article_title,
    article_image: article_image,
    article_visible: article_visible,
    article_content: article_content
  });
}

function ajax_save_edited_article(article_id, element) {
  var edited_content = $(element).closest('.small-article').find('.small-article-content');
  edited_content.find('img').attr({
    class: 'hd-image',
    onclick: 'modal(this)'
  });
  var article_content = edited_content.html();
  $('.articles-start').load("ajax/ajax-save-edited-article.php", {
    article_id: article_id,
    article_content: article_content
  });
}

function ajax_survey_option_decide(article_id, user_choice, element) {
  $(element).closest('.survey-content').load('ajax/ajax-survey-results.php', {
    id: user_choice,
    article_id: article_id
  });
}

function ajax_remove_survey_vote(user_id, article_id, element) {
  $(element).closest('.survey-article').find('.survey-content').load('ajax/ajax-remove-survey-vote.php', {
    user_id: user_id,
    article_id: article_id
  });
}

function ajax_update_article(article_id, element) {
  $(element).find('.name-time small').load("ajax/ajax-update-article.php", {
    article_id: article_id
  });
}

function ajax_toggle_comments(article_id, element) {
  if ($(element).html() == 'Отключить комментарии') {
    $(element).html('Включить комментарии');
  } else {
    $(element).html('Отключить комментарии');
  }
  $(element).closest('.small-article').find('.user-comments-section').load('ajax/ajax-comments-toggle.php', {
    article_id: article_id
  });
}

function ajax_toggle_like(article_id, user_id, element) {
  $(element).toggleClass('like-btn');
  $(element).load("ajax/ajax-likes-new.php", {
    article_id: article_id,
    user_id: user_id
  });
}

function ajax_submit_comment_t(event) {
  event.preventDefault();
  var data = $('#form-t-lvl').serialize();

  $('.user-comments-start').load('ajax/ajax-submit-comment.php', {
    data: data
  });
}

function ajax_submit_comment_s(event) {
  event.preventDefault();
  var data = $('#form-s-lvl').serialize();

  $('.user-comments-start').load('ajax/ajax-submit-comment.php', {
    data: data
  });
}

function ajax_submit_comment_f(event) {
  event.preventDefault();
  var data = $('#form-f-lvl').serialize();

  $('.user-comments-start').load('ajax/ajax-submit-comment.php', {
    data: data
  });
}
