import $ from 'jquery';
import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';
import '@fortawesome/fontawesome-free/css/all.css';
import './css/index.scss';
import './helpers';

$(document).ready(function () {
  setupEvents();
  setupTabHash();
  setupSettingsAvatarImage();
});

/********** Functions **********/
function setupEvents() {
  bsCustomFileInput.init();
  $('select').appSelect();
  $('.infinite-scroll-wrapper').appInfiniteScroll();
  $('.autocomplete-input').appAutocomplete();
  $('.custom-file-input, .custom-file-url-input').appCustomFile();
  $('.collection').appCollection({
    onAddCallback: setupEvents,
  });

  $('.btn-confirm').on('click', function (e) {
    e.preventDefault();

    var href = $(this).attr('href');
    var text = $(this).attr('data-confirm-text');

    var response = confirm(text);
    if (!response) {
      return;
    }

    window.location.href = href;
  });

  $('.btn-prompt-add-query').on('click', function (e) {
    e.preventDefault();

    var href = $(this).attr('href');
    var text = $(this).attr('data-promt-add-query-text');
    var parameter = $(this).attr('data-promt-add-query-parameter');
    var defaultValue = $(this).attr('data-promt-add-query-default-value');

    var response = prompt(text, defaultValue);
    if (response === null) {
      return;
    }

    window.location.href = href +
      (href.indexOf('?') !== -1 ? '&' : '?') +
      parameter + '=' + response;
  });
}

function setupSettingsAvatarImage() {
  if ($('#settings_image_avatarImage').length === 0) {
    return;
  }

  $('.avatars-selector .single-avatar-image').on('click', function () {
    var name = $(this).attr('data-name');

    $('.avatars-selector .single-avatar-image').removeClass('selected');
    $(this).addClass('selected');

    $('#settings_image_avatarImage').val(name);
  });
}

function setupTabHash() {
  if (!window.location.hash) {
    return;
  }

  var tab = window.location.hash + '-tab';
  if ($(tab).length) {
    $(tab).trigger('click');
  }
}
