/*
 *  Document   : translations.js
 *  Author     : Jérémy Dillenbourg (BBDO)
 *  Description: Manage translation editor
 *
 */

// Import global dependencies
import './../bootstrap.js';


export default class Translations {

  static run() {
    jQuery(document).on('.js-translation-tabs', 'click', function(e) {
      e.preventDefault()

      Translations.loadTranslation( jQuery(this).attr('href'))
    })
  }

  static loadTranslation(route) {

    jQuery.ajax({
      url: route,
      type: 'GET',
      success: function(result) {
        $('.js-translation-content-tab').html(result.html);
      }
    })
  }

}