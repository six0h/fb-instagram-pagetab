/*///////////////*/
/*
/* AUTHOR:	Cody Halovich (cody at telenova dot ca)
/* CLIENT:	Chillspace Print. Web. IT. for Think! Social Media
/* PROJECT:	SunPeaks Instagram Photo App
/*
/* DO NOT EDIT THIS DOCUMENT OR ANY FILES RELATED TO THE PARENT PROJECT WITHOUT PERMISSION OF THE AUTHOR.
/*
/*///////////////*/

$(function() { // ENCAPSULATE EVERYTHING IN JQUERY, EVEN FUNCTIONS

/*///////////////////////////////////////////////
/*///////////////////////////////////////////////
// GLOBALS FIRST
/*///////////////////////////////////////////////
/*///////////////////////////////////////////////

// DEFINE GLOBALS
var	pages = $('#page-wrapper>div'),
	page_tab = 'https://dev.telenova.ca/spinstagram/www/home.php',
	channel = '//dev.telenova.ca/spinstagram/www/channel.html',
	app_id = '499113003466607',
	base_url = 'https://dev.telenova.ca/spinstagram/www/';

$('.entry a').fancybox({
	beforeLoad: function() {
		var photoId = this.element[0].firstChild.id;
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: { id: photoId },
			url: 'ajax/getPhoto.php',
			success: function(res) {
				console.log(res);
				if(res.status == 200) {
//					var uri = encodeURIComponent('http://pinterest.com/pin/create/button/?url='+base_url+'home.php?id='+res.photo.id+'&description='+res.photo.text)
					$('#modal #toolbox').html("<a class='addthis_button_facebook_like' fb:like:layout='button_count' addthis:url='"+res.photo.link+"'></a> <a class='addthis_button_tweet' addthis:url='"+res.photo.link+"'></a> <a class='addthis_button_pinterest_pinit' addthis:url='"+res.photo.images.link+"'></a> <a class='addthis_counter addthis_pill_style' addthis:url='"+res.photo.images.link+"'></a>");
					$('#modal .content').html('').append("<div class='left-side'><img src='"+res.photo.images.low+"' alt='"+res.photo.text+"' addthis:url='"+base_url+"test.php'><div class='addthis_toolbox addthis_default_style '> <a class='addthis_button_facebook_like' fb:like:layout='button_count'></a> <a class='addthis_button_tweet'></a> <a class='addthis_button_pinterest_pinit'></a> <a class='addthis_counter addthis_pill_style'></a> </div></div><div class='right-side'><h2>"+res.photo.text+"</h2><div class='fb-comments' data-href='"+base_url+"home.php?id="+res.photo.id+"' data-width='335' data-num-posts='4'></div><br class='clear'/></div>");
					if(window.addthis) {
						console.log('updating addthis');
						window.addthis.toolbox('#toolbox');
					}
				} else {
					$('#modal .content').html('').append('Awwww snap! Something failed. Try again, k?');
					return false;
				}
				FB.XFBML.parse(document.getElementById('modal'));
			}
		})
	},
	afterLoad: function() {
		FB.Canvas.setAutoGrow();
	},
	width: '665px',
	height: '400px',
	autoSize: false
})


/*///////////////////////////////////////////////
/*
/* Initialize Facebook 
/*
/*///////////////////////////////////////////////

fbinit();

	function fbinit() {	

		window.fbAsyncInit = function() {
		     
			var animated = 0;
		     FB.init({
		      appId	 : app_id,	
		      channelUrl : channel, // Channel File
		      status     : true, // check login status
		      cookie     : true, // enable cookies to allow the server to access the session
		      xfbml      : true,  // parse XFBML
		      oauth	 : true
		    });

		    // MAKE CANVAS AUTOGROW
			FB.Canvas.setAutoGrow();


		};

		// Load the SDK Asynchronously
		(function(d){
			var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement('script'); js.id = id; js.async = true;
			js.src = "//connect.facebook.net/en_US/all.js";
			ref.parentNode.insertBefore(js, ref);
		}(document));

	}

});

