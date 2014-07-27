// Once we are ready
$(document).ready(function() {
  // Form handlers
  function bindForm(form_selector) {
    // Contact form cake
    $(form_selector + ' input[name=cake]').val('OK');
    
    // AJAX submit
    var form_options = {
      dataType: 'xml',
      beforeSubmit: function() {
        // Disable inputs
        $(form_selector).find('input, textarea').attr('disabled', true);
        
        // Show wait icon
        $(form_selector + ' div.back').hide();
        $(form_selector + ' div.wait').show();
      },
      success: function(data) {
        // Hide wait icon
        $(form_selector + ' div.wait').hide();
        
        // Read data
        var send_status = $(data).find('status').text() || '0';
        var send_message = $(data).find('message').text() || 'Network error.';
        
        // Apply status
        if(send_status == '1') {
          $(form_selector + ' .userchange').val('');
          $(form_selector + ' div.back').removeClass('error').addClass('ok');
        }
        
        else {
          // Enable back submit input
        $(form_selector + ' input, ' + form_selector + ' textarea').removeAttr('disabled');
          
          // Error marker
          $(form_selector + ' div.back').removeClass('ok').addClass('error');
        }
        
        // Apply message
        $(form_selector + ' div.back').text(send_message);
        $(form_selector + ' div.back').fadeIn('fast');
      }
    };
    
    $(form_selector).submit(function() {
      $(this).ajaxSubmit(form_options);
      
      return false;
    });
  }
  
  // Contact form
  bindForm('#contact');
});