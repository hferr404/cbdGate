   
$(document).ready(function()
{
    $("#show_hide").click(function(){
    $("#men_ex").animate({width:"toggle"});
    });

    // $("#show_ex2").click(function(){
    //   $("#men_ex2").animate({width:"toggle"});
    //   });

    $("#show_hideback").click(function(){
      $("#men_exback").animate({height:"toggle"});
    });

    $('#jojoback').on({
      'click': function() {
           var src = ($(this).attr('src') === 'image/arrowbot.png')
              ? 'image/arrowtop.png'
              : 'image/arrowbot.png';
           $(this).attr('src', src);
      }
    });


    $('#jojo').on({
      'click': function() {
           var src = ($(this).attr('src') === 'image/arrowleft.png')
              ? 'image/arrow.png'
              : 'image/arrowleft.png';
           $(this).attr('src', src);
      }
    });


    $('#jojo2').on({
      'click': function() {
           var src = ($(this).attr('src') === 'http://localhost/SYMFONY/cbdGate/public/image/arrowleft.png')
              ? 'http://localhost/SYMFONY/cbdGate/public/image/arrow.png'
              : 'http://localhost/SYMFONY/cbdGate/public/image/arrowleft.png';
           $(this).attr('src', src);
      }
    });
    
    
    // $( "#image-neon" ).mouseenter(function() {
    //   $("#image-neon").flip({
    //     axis: 'x',
    //     trigger: 'hover',
    //     reverse: true
    //   });
    // });
  
});


var chaine = 'B O U T I Q U E S ' ; 
    var nb_car = chaine.length; 
    var tableau = chaine.split("");
    texte = new Array;
    var txt = '';
    var nb_msg = nb_car - 1;
    for (i=0; i<nb_car; i++) {
    texte[i] = txt+tableau[i];
    var txt = texte[i];
    }
    
    actual_texte = 0;
    function changeMessage()
    {
    document.getElementById("citation1").innerHTML = texte[actual_texte];
    actual_texte++;
    if(actual_texte >= texte.length)
    actual_texte = nb_msg;
    }
    if(document.getElementById)
    

    setInterval("changeMessage()",100);


