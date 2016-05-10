jQuery(document).ready(function($){
    $('.wp-color-picker-field').wpColorPicker();

    jQuery(document).on('click', '#dropshop-background-colour a', function(){
      var color = $(this).attr('data-color');
      $('#dropshop-background-colour a.selected').removeClass('selected')
      $(this).addClass('selected')
      $('input[name="dropshop_page_background_color"]').val(color);
      return false
    })
});