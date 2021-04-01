   
$(document).ready(function()
{
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

        // $(document).load('body');
        // $('#image-neon').animate(
        //     {
        //         'opacity' : '1'
        //     },100);    

        // $(document).load('body');
        // $('#image-neon').animate(
        // {
        //   'opacity' : '1'
        // },100);


        // $("button").load(() => {
        //   var div = $("#image-neon"); 
        //   div.animate({left: '100px'}, "slow");
        //   div.animate({fontSize: '4em'}, "slow");
        // });
  
});


var chaine = 'B O U T I Q U E ' ; 
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
    
    setInterval("changeMessage()",250);