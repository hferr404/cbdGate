   
$(document).ready(function(){
    $("#show_hide").click(function(){
      $("#men_ex").animate({width:"toggle"});
    });

    $('#jojo').on({
      'click': function() {
           var src = ($(this).attr('src') === 'image/arrowleft.png')
              ? 'image/arrow.png'
              : 'image/arrowleft.png';
           $(this).attr('src', src);
      }
    });

 
});

