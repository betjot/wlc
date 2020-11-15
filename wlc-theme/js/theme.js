// set header heignt for fixed header
(function ( $ ) {
    var setHeight = function() {
        var top = $('header').outerHeight();
        $('#primary').css({'padding-top': top + 'px'});
      }
      
      $(window).load(function() {
        //On load you can be sure that the target element has been loaded 
        //(except if it is loaded from an ajax call)
        setHeight();
      });
      
      $(window).resize(function() {
        setHeight();
      });
}) ( jQuery );


(function ( $ ) {
    var $el = $('.entry-header');
    $(window).on('scroll', function () {
        var scroll = $(document).scrollTop();
        $el.css({
            'background-position':'50% '+(-.4*scroll)+'px'
        });
    });
}) ( jQuery );

console.log('hshs');