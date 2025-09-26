$(document).ready(function(){

    $(".navbar-nav-link").each(function() {
        if ($(this).attr('href') == window.location.href) {
            $(this).addClass("active");
        }
    });
    
    $(".menu-link").each(function() {

        if ($(this).attr('href') == window.location.href) {

           $(this).parent().addClass('active');
           $(this).parentsUntil('top-parent').addClass('open active');
           $(this).addClass('menu-link-active');
          
        }

    });

    $("#lg_open").on("click",function(){

        $( '.layout-menu' ).each(function () {

            this.style.setProperty( 'margin-left', '0', 'important' );
        });

    });

    $("#lg_back").on("click",function(){

        $( '.layout-menu' ).each(function () {

            this.style.setProperty( 'margin-left', '-17rem', 'important' );
        });
    });


})