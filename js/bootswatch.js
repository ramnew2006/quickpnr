$('a[rel=tooltip]').tooltip({
	'placement': 'bottom'
});


$('.navbar a, .subnav a').smoothScroll();


(function ($) {

	$(function(){

		// fix sub nav on scroll
		var $win = $(window),
				$body = $('body'),
				$nav = $('.subnav'),
				navHeight = $('.navbar').first().height(),
				subnavHeight = $('.subnav').first().height(),
				subnavTop = $('.subnav').length && $('.subnav').offset().top - navHeight,
				marginTop = parseInt($body.css('margin-top'), 10);
				isFixed = 0;

		processScroll();

		$win.on('scroll', processScroll);

		function processScroll() {
			var i, scrollTop = $win.scrollTop();
			
			if ($(window).width()<=760){ //If opened from a mobile device fix the body top margin
				$body.css('margin-top', '65px');
			}else{
				if (scrollTop >= subnavTop && !isFixed) {
					isFixed = 1;
					$nav.addClass('subnav-fixed');
					$body.css('margin-top', marginTop + subnavHeight + 'px');
					//$body.css('margin-top', '100px');
				} else if (scrollTop <= subnavTop && isFixed) {
					isFixed = 0;
					$nav.removeClass('subnav-fixed');
					$body.css('margin-top', marginTop + 'px');
					//$body.css('margin-top', '30px');
				}
			}
			
			$(window).resize(function() {
				if ($(window).width()<=760){
					$body.css('margin-top', '65px');
				}else{
					if (scrollTop >= subnavTop && !isFixed) {
						isFixed = 1;
						$nav.addClass('subnav-fixed');
						$body.css('margin-top', marginTop + subnavHeight + 'px');
						//$body.css('margin-top', '100px');
					} else if (scrollTop <= subnavTop && isFixed) {
						isFixed = 0;
						$nav.removeClass('subnav-fixed');
						$body.css('margin-top', marginTop + 'px');
						//$body.css('margin-top', '30px');
					}
				}
			});
		}

	});

})(window.jQuery);