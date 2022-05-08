/*jshint jquery:true */

$(document).ready(function($) {
	"use strict";

	/* global google: false */
	/*jshint -W018 */

	
	/*-------------------------------------------------*/
	/* =  preloader function
	/*-------------------------------------------------*/
	
	$('body').ready(function(){
		var mainDiv = $('#container');
		mainDiv.delay(400).addClass('active');
	});

	/*-------------------------------------------------*/
	/* =  search animate
	/*-------------------------------------------------*/

	var searchButton = $('button.search-icon');

	searchButton.on('click', function(event) {
		event.preventDefault();

		var searchBar = $('.search_bar'),
			$this = $(this);
		if ( !$this.hasClass('opened') ) {
			$this.addClass('opened');
			$this.find('.open-search').fadeOut(0);
			$this.find('.close-search').fadeIn(0);
			searchBar.fadeIn(400);
			searchBar.find("input[type='search']").focus();
		} else {
			$this.removeClass('opened');
			$this.find('.open-search').fadeIn(0);
			$this.find('.close-search').fadeOut(0);
			searchBar.fadeOut(400);
		}
	});

	/*-------------------------------------------------*/
	/* =  nav animate
	/*-------------------------------------------------*/

	var ToogleMenu = $('a.mobile-nav-toggle');

	ToogleMenu.on('click', function(event) {
		event.preventDefault();

		var containerMover = $('#container'),
			mobileMenu = $('.mobile-menu'),
			$this = $(this);
		if ( !$this.hasClass('opened') ) {
			$this.addClass('opened');
			containerMover.addClass('move');
			mobileMenu.addClass('open');
		} else {
			$this.removeClass('opened');
			containerMover.removeClass('move');
			mobileMenu.removeClass('open');
		}
	});

	/*-------------------------------------------------*/
	/* =  toggle course-panel-content
	/*-------------------------------------------------*/

	// var panelHeading = $('.course-panel-heading');

	// panelHeading.on('click', function() {
	// 	$(this).toggleClass('active');
	// });

	/* ---------------------------------------------------------------------- */
	/*	magnific-popup
	/* ---------------------------------------------------------------------- */

	// Example with multiple objects
	// function callMagnificpopup() {
	// 	$('.zoom').magnificPopup({
	// 		type: 'image',
	// 		gallery: {
	// 			enabled: true
	// 		}
	// 	});
	// }
	
	// callMagnificpopup();

	// // Example with multiple objects
	// $('.preview-button').magnificPopup({
	// 	type: 'iframe'
	// });

	/*-------------------------------------------------*/
	/* = skills animate
	/*-------------------------------------------------*/

	var skillBar = $('.skills-box');
	skillBar.appear(function() {

		var animateElement = $(".meter > p");
		animateElement.each(function() {
			$(this)
				.data("origWidth", $(this).width())
				.width(0)
				.animate({
					width: $(this).data("origWidth")
				}, 1200);
		});

	});

	/*-------------------------------------------------*/
	/* =  count increment
	/*-------------------------------------------------*/

	$('.statistic-post').appear(function() {
		$('.timer').countTo({
			speed: 4000,
			refreshInterval: 60,
			formatter: function (value, options) {
				return value.toFixed(options.decimals);
			}
		});
	});

	/*-------------------------------------------------*/
	/* =  comming soon & error height fix
	/*-------------------------------------------------*/

	var dateCount = $('.countdown-item').attr('data-date');

	$('.countdown-item').countdown(dateCount, function(event) {
		var $this = $(this);
		switch(event.type) {
			case "seconds":
			case "minutes":
			case "hours":
			case "days":
			case "daysLeft":
				$this.find('span#'+event.type).html(event.value);
				break;
			case "finished":
				$this.hide();
				break;
		}
	});
	
	/*-------------------------------------------------*/
	/* =  OWL carousell
	/*-------------------------------------------------*/

	var owlWrap = $('.owl-wrapper');

	if (owlWrap.length > 0) {

		if (jQuery().owlCarousel) {
			owlWrap.each(function(){

				var carousel= $(this).find('.owl-carousel'),
					dataNum = $(this).find('.owl-carousel').attr('data-num'),
					dataNum2,
					dataNum3;

				if ( dataNum == 1 ) {
					dataNum2 = 1;
					dataNum3 = 1;
				} else if ( dataNum == 2 ) {
					dataNum2 = 2;
					dataNum3 = dataNum - 1;
				} else {
					dataNum2 = dataNum - 1;
					dataNum3 = dataNum - 2;
				}

				carousel.owlCarousel({
					autoPlay: 10000,
					navigation : true,
					items : dataNum,
					itemsDesktop : [1199,dataNum2],
					itemsDesktopSmall : [991,dataNum3],
					itemsTablet : [768, dataNum3],
				});

			});
		}
	}
	

	/* ---------------------------------------------------------------------- */
	/*	Register radio buttons
	/* ---------------------------------------------------------------------- */

	$('#radioBtn a').on('click', function(){
		var sel = $(this).data('title');
		var tog = $(this).data('toggle');
		$('#'+tog).prop('value', sel);
		
		$('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
		$('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');

		var val = $("#type").attr("value");
				
		if (val=='jur') {
			$(".jur").css("display", "flex");
			$(".jinput").attr("required", "true");
		} else {
			$(".jur").css("display", "none");
			$(".jinput").removeAttr('required');
		}	
	})

	/* ---------------------------------------------------------------------- */
	/*	Register password field toggle
	/* ---------------------------------------------------------------------- */

	$("#show_hide_password a").on('click', function (event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass("fa-eye-slash");
            $('#show_hide_password i').removeClass("fa-eye");
        } else if ($('#show_hide_password input').attr("type") == "password") {
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass("fa-eye-slash");
            $('#show_hide_password i').addClass("fa-eye");
        }
    });

    $("#show_hide_newpassword a").on('click', function (event) {
        event.preventDefault();
        if ($('#show_hide_newpassword input').attr("type") == "text") {
            $('#show_hide_newpassword input').attr('type', 'password');
            $('#show_hide_newpassword i').addClass("fa-eye-slash");
            $('#show_hide_newpassword i').removeClass("fa-eye");
        } else if ($('#show_hide_newpassword input').attr("type") == "password") {
            $('#show_hide_newpassword input').attr('type', 'text');
            $('#show_hide_newpassword i').removeClass("fa-eye-slash");
            $('#show_hide_newpassword i').addClass("fa-eye");
        }
    });

	/* ---------------------------------------------------------------------- */
	/*	Register masks
	/* ---------------------------------------------------------------------- */

	$('#phone').mask('+0 (000) 000-00-00');
	$('#inn').mask('000000000000');
	$('#kpp').mask('000000000');
	$('#rs').mask('00000000000000000000');
	$('#bic').mask('000000000');
	$('#ks').mask('00000000000000000000');

});

function Resize() {
	$(window).trigger('resize');
}

