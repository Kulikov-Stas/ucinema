$(function(){

	//carousels
	var itemsCount = $('.fw-carousel *').length;
	console.log(itemsCount);
	var y = 0;
	var x = 0;
	if (itemsCount == 2) {
		$('.pager').remove()

	for (var i = 1; i < 3; i++) {
		$('.fw-carousel img:nth-of-type('+i+')').clone().appendTo('.fw-carousel')
		};
		for (var j = 1; j < 7; j++) {
		$('.mid-carousel div:nth-of-type('+j+')').clone().appendTo('.mid-carousel');
		};
		y=1;
	};

	if (itemsCount == 3) {
		for (var j = 1; j < 7; j++) {
		$('.mid-carousel div:nth-of-type('+j+')').clone().appendTo('.mid-carousel');
		};
		x = -1;
	};

	if (itemsCount == 4) {
		for (var j = 1; j < 5; j++) {
		$('.mid-carousel div:nth-of-type('+j+')').clone().appendTo('.mid-carousel');
		};
		x = -2;
		y= 1;
	};

	if (itemsCount == 5) {
		for (var j = 1; j < 6; j++) {
		$('.mid-carousel div:nth-of-type('+j+')').clone().appendTo('.mid-carousel');
		};
		x = -3;
		y = 2;
	};	

	if (itemsCount == 6) {	
		for (var j = 1; j < 7; j++) {
		$('.mid-carousel div:nth-of-type('+j+')').clone().appendTo('.mid-carousel');
		};
		x = -4;
		y = -3;
	};

	if (itemsCount == 7) {	
		x = -5;
		y = -3;
	};	

	$('.fw-carousel').carouFredSel({
		width: '100%',
		height: 525,

		items: {
			visible: 3,
			start: x,
			minimum:1,
		},
		scroll: {
			items: 1,
			duration: 400,
			timeoutDuration: 5000
		},
		// prev: '.prev',
		// next: '.next',
		pagination: {
			container: '.pager',
			deviation: 1,
		// pauseOnHover:true,
		}
	});


	var defaultCss = {
		width: 290,
		height: 145,
		marginTop: 86,
		marginRight: 0,
		marginLeft: 0,
		opacity: 0.6,

	};
	var selectedCss = {
		width: 450,
		height: 252,
		marginTop: 36,
		marginRight: 0,
		marginLeft: -85,
		opacity: 1,

	};
	var aniOpts = {
		queue: false,
		duration:0,
		// easing: 'elastic'
	};
	
	var $car = $('.mid-carousel');
	$car.find('img').css('zIndex', 1).css( defaultCss );
	$car.find('img').eq(3).css('zIndex', 2).css( selectedCss );
	$car.find('div').eq(3).addClass('active');
	$car.carouFredSel({
		circular: true,
		infinite: true,
		width: '100%',
		height: 300,
		items:{
			visible:7,
			minimum:5
		},
		// prev: '.mid-prev',
		// next: '.mid-next',
		// 	pagination: {
		// 		container: '.mid-pager',
		// 		deviation: 1,
		// 	pauseOnHover:true,
		// },
		auto: true,
		scroll: {
			queue:false,
			items: 1,
			duration: 400,
			timeoutDuration:5000,
			// easing:'elastic',
			height:300,
			onBefore: function( data ) {
				data.items.old.eq(3).find('img').css('zIndex', 1).animate( defaultCss, aniOpts );
				data.items.old.eq(3).removeClass('active');
				data.items.visible.eq(3).find('img').css('zIndex', 3).animate( selectedCss, aniOpts );
				data.items.visible.eq(3).addClass('active');	
			},
		}
	});

	$('.pager a').click(function(){
		$(".mid-carousel").trigger("slideTo", $(this).index('.pager a')+y);
	})

	//post-form-effect
	$('.post').click(function(){
		$('.post-form').toggleClass('form-visible');

		$('.post-form').effect('bounce',{
			times:7,
			direction:'right',
			distance:50
		},700);
	});



	//tabs
	$( "#tabs" ).tabs({
		active:0
	});

	$('.tab-item1').find('.count-films').show();

	$('#tabs > ul li').click(function(){
		$('.tab-item1,.tab-item2,.tab-item3').find('.count-films').hide();
		$(this).find('.count-films').toggle();
	});

	//fancybox
	$("a[rel=group]").fancybox({
		prevEffect: 'fade',
    	nextEffect: 'fade',
    	openEffect:'fade',
    	helpers:{
    		overlay: {
    			locked: false
    		}
    	}  
	});

	$("a.film-poster").fancybox({
    	helpers:{
    		overlay: {
    			locked: false
    		}
    	}  		
	});

	//logo-hover
	$('.logo img').mouseenter(function(){
		$(this).atr('src', '/images/logo-hover.png');
	});
	$('.logo img').mouseleave(function(){
		$(this).attr('src', '/images/logo.png');
	});

	//placeholders
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
        }).blur().parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            })
        });

    //scrolling
  	$('.scroll-to-top').click(function() {
  		$("html, body").animate({ scrollTop: 0 }, "slow");
  		return false;
	});

  	var scroll = $(window).scrollTop();
	$(window).scroll(function(){
		if ( $(this).scrollTop() >= $('.content').offset().top ){
			$('.scroll-to-top').fadeIn('fast');
		}
		else {
			$('.scroll-to-top').fadeOut('fast');
		}
	});

   
 $('.mid-carousel>div').on('click',function(){
    if ($(this).hasClass('active')){ 
     var url = $(this).children('a').attr('href');
     console.log(url);
     window.location = url;
     }
     
 });
});