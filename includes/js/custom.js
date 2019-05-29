$(document).ready(function(){
    
   
    $('ul.navigation>li>a').hover(function() {
            $(this).stop().animate({color: "#076633"}, 500);
    }, function() {
            $(this).stop().animate({color: "#ffffff"}, "fast");
    });
    
    $('ul.navigation>li>a').mouseenter(function() {
	if($(this).parent('li').hasClass('item-hover')) {
	    $(this).parent('li').children('ul.dpor-menu').fadeTo(500, 1);
	} else {
	    $(this).parent('li').parent('ul').find('li.item-hover').removeClass('item-hover').children('ul.dpor-menu').fadeOut("fast");
	    $(this).parent('li').children('ul.dpor-menu').fadeTo(500, 1);
	    $(this).parent('li').addClass('item-hover');
	}
	
    });
    $('ul.dpor-menu').mouseleave(function() {
	$(this).fadeOut("fast");
	$(this).parent('li').removeClass('item-hover');
    });
    
    $('ul.footer-navigation>li>a').hover(function() {
            $(this).stop().animate({color: "#000000"}, 1000);
    }, function() {
            $(this).stop().animate({color: "#2c2c2c"}, "fast");
    });
    
    $('a.go').hover(function() {
            $(this).stop().animate({paddingLeft: 15}, "fast");
    }, function() {
            $(this).stop().animate({paddingLeft: 0}, "fast");
    });
    
        $('a.go.second-step').live("click", function() {
            if(CheckRequired($('div.first-step')))
            {
                $(this).removeClass('second-step').addClass('third-step');
                $('a.back-to.first-step').fadeIn();
                $('div.first-step').animate({left: -700},1100 );
                $('div.second-step').animate({left: 0}, 1100 );
            }
        });
        $('a.back-to.first-step').live("click", function() {
            $('a.go.third-step').removeClass('third-step').addClass('second-step');
            $('div.first-step').animate({left: 0},1100 );
            $('div.second-step').animate({left: 700}, 1100 );
            $('a.back-to.first-step').fadeOut();
        });
        $('a.go.third-step').live("click", function() {
            if(CheckRequired($('div.second-step')))
            {
                $(this).fadeOut();
                $('a.back-to.first-step').removeClass('first-step').addClass('second-step');
                $('div.second-step').animate({left: -700},1100 );
                $('div.third-step').animate({left: 0}, 1100 );
                
                //          
                $.post('/for_php/ajax.php', {action:'calculation', departure:$('#departure').val(), arrival:$('#arrival').val(), name:$('#name').val(), weight:$('#weight').val(), transport:$('#transport').val(), pack:$('#package').val(),
                    company:$('#company').val(), client:$('#client').val(), phone:$('#phone').val(), email:$('#email').val(), text:$('#text').val()}, 
                    function(response)
                    {
                        if(response)
                        {
                            //var d = JSON.parse(response);
                            $('#departure').val('');$('#arrival').val('');$('#name').val('');$('#weight').val('');$('#transport').val('');$('#package').val('');
                            $('#company').val('');$('#client').val(''); $('#phone').val('');$('#email').val('');$('#text').val('');
                        }
                    });
            }
        });
        $('a.back-to.second-step').live("click", function() {
            $(this).removeClass('second-step').addClass('first-step');
            $('a.go.third-step').removeClass('third-step').addClass('third-step').fadeIn();
            $('div.second-step').animate({left: 0},1100 );
            $('div.third-step').animate({left: 700}, 1100 );
        });
    
    
    $('div.i-open').click(function(){
	$('body').append('<div class="mask"><!-- mask(0.7) --></div>');
	$('body').find('div.mask').css({height: $('html').height()})
	$('body').find('div.mask').fadeTo("slow", 0.7);
	$('div.window').fadeIn("slow");
    });
    
    $('div.mask').live("click", function(){
	$(this).fadeTo("slow", 0, function(){$(this).remove();});
	$('div.window').fadeOut("slow");
    });
    
    
    /* =============================================================================
	poshytip
	========================================================================== */
    $('.show-title').poshytip({
        className: 'tip-twitter',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'center',
	offsetY: 11,
	allowTipHover: false
        
    });
    /* =============================================================================
	placeholder
	========================================================================== */
    if(!Modernizr.input.placeholder){
        $('[placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function() {
        var input = $(this);
        if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
        }
        }).blur();
        $('[placeholder]').parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            })
        });
    }
});