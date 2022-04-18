jQuery(function($){

    $('.article-container.category').append( '<span class="load-more"></span>' );
    var button = $('.article-container.category .load-more');
    var page = 2;
    var loading = false;
    var scrollHandling = {
	allow: true,
	reallow: function() {
	    scrollHandling.allow = true;
	},
	delay: 250 //(milliseconds) adjust to the highest acceptable value
    };

    $(window).scroll(function(){
	if( ! loading && scrollHandling.allow ) {
	    scrollHandling.allow = false;
	    setTimeout(scrollHandling.reallow, scrollHandling.delay);
	    var offset = $(button).offset().top - $(window).scrollTop();
	    if( 2000 > offset ) {
		loading = true;
		var data = {
		    action: 'cvonk_thumb_loadmore_ajax',  // calls functions.php:cvonk_thumb_loadmore_ajax()
		    page: page,
		    query: cvonk_thumb_loadmore.query,
		    nonce: cvonk_thumb_loadmore.nonce
		};
		$.post(cvonk_thumb_loadmore.url, data, function(res) {
		    if( res.success) {
			$('.article-container').append( res.data );
			$('.article-container').append( button );
			page = page + 1;
			loading = false;
		    } else {
			console.log(res);
		    }
		}).fail(function(xhr, textStatus, e) {
		    console.log(xhr.responseText);
		});

	    }
	}
    });
    
});
