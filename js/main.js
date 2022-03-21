var limit = 5;
var recentScrollComments = false;
var recentScrollTime = false;
var recentScroll = false;
var keepUpdating = true;

function scrollWin(Y, X) {
  window.scroll({ top: Y, left: X, behavior: 'smooth' });
}

function resize(element) {
  if ($(element).css('max-height') == '100%' && $(element).css('cursor') == 'zoom-in') {
    $(element).css({
      'max-height': '300%',
      'cursor': 'zoom-out'
    });
  } else {
    $(element).css({
      'max-height': '100%',
      'cursor': 'zoom-in'
    });
  }
}

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

function OpenSidebar(element) {
  $(element).toggleClass('active');
  var width = $(window).width();
  if (width <= 768) {
    var value = '-100vw';
  } else {
    var value = '-300px';
  }

  if ($(element).hasClass('active')) {
    $('.nav-sidebar').css('right', '0px');
  } else {
    $('.nav-sidebar').css('right', value);
    $('.nav-sidebar').removeAttr('style');
  }
}

function CloseSidebar(element) {
  $('.nav-bars').removeClass('active');
  var width = $(window).width();
  if (width <= 768) {
    var value = '-100vw';
  } else {
    var value = '-300px';
  }

  $('.nav-sidebar').parent().css('right', value);
  $('.nav-sidebar').removeAttr('style');
}

function OpenSearch(element) {
  $(element).toggleClass('active');
  $('.nav-search-sidebar').css('display', 'flex');
  $('.nav-search-sidebar').hide()
  $('.nav-search-sidebar').fadeIn();
}

function CloseSearch(element) {
  $(element).parent().hide();
}

function OpenTab(element) {
  if (element === 1) {
    $('.forms-options div:first-child').addClass('active');
    $('.forms-options div:last-child').removeClass('active');
    $('.login-form').addClass('active');
    $('.signup-form').removeClass('active');
  } else {
    $('.forms-options div:last-child').addClass('active');
    $('.forms-options div:first-child').removeClass('active');
    $('.signup-form').addClass('active');
    $('.login-form').removeClass('active');
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

function toggle_visible(element) {
  if ($(element).html() == '<i class="fad fa-eye"></i>') {
    $(element).html('<i class="fad fa-eye-slash"></i>');
    $(element).attr('title', 'Закрыто');
    $("input[name=visible]").attr('value', '0');
  } else {
    $(element).html('<i class="fad fa-eye"></i>');
    $(element).attr('title', 'Открыто');
    $("input[name=visible]").attr('value', '1');
  }
}

function toggle_night_mode() {
  $('body').toggleClass('night');
  $('div.navbar').toggleClass('night');
  $('div.main-navbar-container').toggleClass('night');
  $('div.author-border').toggleClass('night');
  $('div.author-name-image').toggleClass('night');
}

function header_to(article_id, user_id, user_id_check, element) {
  if (user_id == user_id_check) {
    window.location.replace("profile?article_id=" + article_id);
  } else {
    window.location.replace("profile?id=" + user_id + "&article_id=" + article_id);
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
  $("button[name=article-button]").html('Опубликовать');
  $('#article-add').attr('onsubmit', 'ajax_add_article(event)');
}

function upload_custom_image(element) {
  var data = $("input[name=upload-custom-image]").prop('files')[0];
  var form_data = new FormData();
  form_data.append('file', data);
  $.ajax({
    url: 'ajax/ajax-upload-image.php',
    dataType: 'text',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(php_script_response) {
      $('#editor').append('<img src="media/uploads/backgrounds/'+ php_script_response +'">')
    }
  });
}

function large_article_image(element) {
  var data = $("input[name=custom-article-image]").prop('files')[0];
  var form_data = new FormData();
  form_data.append('file', data);
  $.ajax({
    url: 'ajax/ajax-upload-image.php',
    dataType: 'text',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(php_script_response) {
      $("input[name=article-image]").val(php_script_response);
      $("input[name=article-image]").prop('disabled', true);
      $("input[name=custom-article-image]").removeAttr('onchange');
      $("input[name=custom-article-image]").attr('onclick', 'large_article_image_remove(this)');
      $("input[name=custom-article-image]").attr('type', 'text');
      $("input[name=custom-article-image]").next().attr('class', 'fas fa-times');
    }
  });
}

function large_article_image_remove(element) {
  var data = $("input[name=article-image]").val();

  $.post('ajax/ajax-remove-image.php', {
    image: data
  }, function(data, status) {
    $("input[name=custom-article-image]").next().attr('class', 'fas fa-image');
    $("input[name=custom-article-image]").attr('type', 'file');
    $("input[name=custom-article-image]").removeAttr('onclick');
    $("input[name=custom-article-image]").attr('onchange', 'large_article_image(this)');
    $("input[name=article-image]").removeAttr('disabled');
    $("input[name=article-image]").val('');
  });
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

function reply_to_comment(comment_author, comment_uid, element) {
  $(element).closest('.comments-container').find('form textarea[name=comment_content]').val(comment_author + ', ');
}

function ajax_survey_option_decide(article_id, user_choice, element) {
  $(element).closest('.survey-article-content').load('ajax/ajax-survey-results.php', {
    id: user_choice,
    article_id: article_id
  });
}

function ajax_remove_survey_vote(user_id, article_id, element) {
  $(element).closest('.survey-article').find('.survey-article-content').load('ajax/ajax-remove-survey-vote.php', {
    user_id: user_id,
    article_id: article_id
  });
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
    var article_image_custom = $('#editor').find("input[name=custom-article-image]").val();
    $("input[name=custom-article-image]").next().attr('class', 'fas fa-image');
    $("input[name=custom-article-image]").attr('type', 'file');
    $("input[name=custom-article-image]").removeAttr('onclick');
    $("input[name=custom-article-image]").attr('onchange', 'large_article_image(this)');
    $("input[name=article-image]").removeAttr('disabled');
    $("input[name=article-image]").val('');

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
    custom_article_image: article_image_custom,
    data: data
  });
}

function ajax_update_articles() {
  if ($('.articles-start article:last-child').inView('topOnly', 80) && !recentScroll && keepUpdating) {
    ajax_load_articles();
    recentScroll = true;
    window.setTimeout(() => { recentScroll = false; }, 1000);
  }
}

function ajax_load_articles() {
  var max_limit = $('body').attr('dt-max-limit');
  if (limit <= max_limit) {
    limit = limit + 5;
    var pid = $('.header-profile-container').attr('dt-profile-id');
    $('.articles-start').load('ajax/ajax-load-new-articles.php', {
      limit: limit,
      profile_id: pid
    });
  } else {
    keepUpdating = false;
  }
}

function ajax_update_article() {
  $('.small-article, .large-article, .survey-article').each(function() {
    if ($(this).inView('topOnly', 80) && !recentScrollTime) {
      var article_id = $(this).attr('dt-article-id');
      $(this).find('.name-time small').load("ajax/ajax-update-article.php", {
        article_id: article_id
      });
      recentScrollTime = true;
      window.setTimeout(() => { recentScrollTime = false; }, 1000);
    }
  });
}

function ajax_update_comments() {
  $('.small-article, .large-article, .survey-article').each(function() {
    if ($(this).inView('topOnly', 80) && !recentScrollComments) {
      var article_id = $(this).attr('dt-article-id');
      var ch_article_id = $(this).attr('dt-article-control');
      $(this).find('footer .comments-start').load('ajax/ajax-update-comments.php', {
        article_id: article_id,
        ch_article_id: ch_article_id
      });
      recentScrollComments = true;
      window.setTimeout(() => { recentScrollComments = false; }, 000);
    }
  });
}

function ajax_remove_article(article_id, element) {
  var ch_article_id = $(element).closest('.small-article, .large-article, .survey-article').attr('dt-article-control');
  $('.articles-start').load("ajax/ajax-remove-article.php", {
    article_id: article_id,
    ch_article_id: ch_article_id
  });
}

function ajax_edit_article(article_id, element) {
  var ch_article_id = $(element).closest('.small-article').attr('dt-article-control');
  $(element).closest('.small-article').find('.small-article-content').attr("contenteditable", "true");
  $(element).closest('.small-article').find('.small-article-content img').attr({
    class: 'hd-image',
    onclick: 'null'
  });
  $(element).closest('.small-article').find('footer').load('ajax/ajax-save-button.php', {
    article_id: article_id,
    ch_article_id: ch_article_id
  });
}

function ajax_edit_large_article(article_id, element) {
  $('#editor').attr('contenteditable', 'false');
  $("button[name=article-button]").html('Сохранить');
  var ch_article_id = $(element).closest('.large-article').attr('dt-article-control');
  $('#article-add').attr('onsubmit', 'ajax_save_edited_large_article(event, ' + article_id + ', this)');
  $('#editor').load('ajax/ajax-edit-large-article.php', {
    article_id: article_id,
    ch_article_id: ch_article_id
  });
  scrollWin(350, 0);
}

function ajax_save_edited_large_article(event, article_id, element) {
  event.preventDefault();
  var ch_article_id = $('#editor').find('.large-article-form').attr('dt-article-control');
  var article_title = $('#editor').find('input[name=article_title]').val();
  var article_image = $('#editor').find("input[name=article-image]").val();
  var article_visible = $("input[name=visible]").val();
  var article_content = $('#editor').find('.editor').html();
  $('#editor .large-article-form .editor').find('img').attr({
    class: 'hd-image',
    onclick: 'modal(this)'
  });

  console.log(ch_article_id);

  $('#article-add').attr('onsubmit', 'ajax_add_article(event)');
  $('#editor .large-article-form').remove();
  $('#editor').attr('contenteditable', 'true');
  $("button[name=article-button]").html('Опубликовать');

  $('.articles-start').load('ajax/ajax-save-edited-article.php', {
    article_id: article_id,
    article_title: article_title,
    article_image: article_image,
    article_visible: article_visible,
    article_content: article_content,
    ch_article_id: ch_article_id
  });
}

function ajax_save_edited_article(article_id, element) {
  var ch_article_id = $(element).closest('.small-article').attr('dt-article-control');
  $(element).closest('.small-article').find('.small-article-content div, .small-article-content span').removeAttr('style');
  var edited_content = $(element).closest('.small-article').find('.small-article-content');
  edited_content.find('img').attr({
    class: 'hd-image',
    onclick: 'modal(this)'
  });
  var article_content = edited_content.html();
  $('.articles-start').load("ajax/ajax-save-edited-article.php", {
    article_id: article_id,
    article_content: article_content,
    ch_article_id: ch_article_id
  });
}

function ajax_toggle_comments(article_id, element) {
  if ($(element).html() == 'Отключить комментарии') {
    $(element).html('Включить комментарии');
  } else {
    $(element).html('Отключить комментарии');
  }
  var ch_article_id = $(element).closest('.small-article, .large-article, .survey-article').attr('dt-article-control');
  $(element).closest('.small-article, .large-article, .survey-article').find('.article-comments').load('ajax/ajax-comments-toggle.php', {
    article_id: article_id,
    ch_article_id: ch_article_id
  });
}

function ajax_toggle_like(article_id, user_id, element) {
  var ch_article_id = $(element).closest('.small-article, .large-article, .survey-article').attr('dt-article-control');

  $(element).toggleClass('like-total');
  $(element).load("ajax/ajax-likes-new.php", {
    article_id: article_id,
    user_id: user_id,
    ch_article_id: ch_article_id
  });
}

function ajax_submit_comment_f(event, element) {
  event.preventDefault();
  var data = $(element).serialize();
  var ch_article_id = $(element).closest('.small-article, .large-article, .survey-article').attr('dt-article-control');
  $(element).find('textarea').val('');

  $(element).parent().find('.comments-start').load('ajax/ajax-submit-comment.php', {
    data: data,
    ch_article_id: ch_article_id
  });
}

function ajax_remove_comment_f(comment_id, article_id, element) {
  $('.comments-start').load('ajax/ajax-remove-comment.php', {
    comment_id: comment_id,
    article_id: article_id
  });
}
