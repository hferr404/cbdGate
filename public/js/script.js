   
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

     $('#boutique').on('mouseenter', function(){
      
       $(this).css({'background-color':'black',
                    'transition':'all ease 1.5s'});
     });
     $('#boutique').on('mouseleave', function(){
      
       $(this).css({'background-color': 'grey',
                    'transition':'all ease 1.5s'});
     });

     $('#produit').on('mouseenter', function(){
      
      $(this).css({'background-color':'black',
      'transition':'all ease 1.5s'});
     });
     $('#produit').on('mouseleave', function(){
      
      $(this).css({'background-color': 'grey',
      'transition':'all ease 1.5s'});;
     });

     $('#categorie').on('mouseenter', function(){
      
      $(this).css({'background-color':'black',
      'transition':'all ease 1.5s'});
     });
     $('#categorie').on('mouseleave', function(){
      
      $(this).css({'background-color': 'grey',
      'transition':'all ease 1.5s'});
     });
     $('#commentaire').on('mouseenter', function(){
      
      $(this).css({'background-color':'black',
      'transition':'all ease 1.5s'});
     });
     $('#commentaire').on('mouseleave', function(){
  
      $(this).css({'background-color': 'grey',
                    'transition':'all ease 1.5s'});
     });
     $('#membre').on('mouseenter', function(){
      
      $(this).css({'background-color':'black',
      'transition':'all ease 1.5s'});
     });
     $('#membre').on('mouseleave', function(){
      
      $(this).css({'background-color': 'grey',
      'transition':'all ease 1.5s'});
     });
 
});

