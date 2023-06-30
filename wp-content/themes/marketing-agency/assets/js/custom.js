function marketing_agency_menu_open_nav() {
	window.marketing_agency_responsiveMenu=true;
	jQuery(".sidenav").addClass('show');
}
function marketing_agency_menu_close_nav() {
	window.marketing_agency_responsiveMenu=false;
 	jQuery(".sidenav").removeClass('show');
}

jQuery(function($){
 	"use strict";
   	jQuery('.main-menu > ul').superfish({
		delay:       500,
		animation:   {opacity:'show',height:'show'},  
		speed:       'fast'
   	});
});

jQuery(document).ready(function () {
	window.marketing_agency_currentfocus=null;
  	marketing_agency_checkfocusdElement();
	var marketing_agency_body = document.querySelector('body');
	marketing_agency_body.addEventListener('keyup', marketing_agency_check_tab_press);
	var marketing_agency_gotoHome = false;
	var marketing_agency_gotoClose = false;
	window.marketing_agency_responsiveMenu=false;
 	function marketing_agency_checkfocusdElement(){
	 	if(window.marketing_agency_currentfocus=document.activeElement.className){
		 	window.marketing_agency_currentfocus=document.activeElement.className;
	 	}
 	}
 	function marketing_agency_check_tab_press(e) {
		"use strict";
		// pick passed event or global event object if passed one is empty
		e = e || event;
		var activeElement;

		if(window.innerWidth < 999){
		if (e.keyCode == 9) {
			if(window.marketing_agency_responsiveMenu){
			if (!e.shiftKey) {
				if(marketing_agency_gotoHome) {
					jQuery( ".main-menu ul:first li:first a:first-child" ).focus();
				}
			}
			if (jQuery("a.closebtn.mobile-menu").is(":focus")) {
				marketing_agency_gotoHome = true;
			} else {
				marketing_agency_gotoHome = false;
			}

		}else{

			if(window.marketing_agency_currentfocus=="responsivetoggle"){
				jQuery( "" ).focus();
			}}}
		}
		if (e.shiftKey && e.keyCode == 9) {
		if(window.innerWidth < 999){
			if(window.marketing_agency_currentfocus=="header-search"){
				jQuery(".responsivetoggle").focus();
			}else{
				if(window.marketing_agency_responsiveMenu){
				if(marketing_agency_gotoClose){
					jQuery("a.closebtn.mobile-menu").focus();
				}
				if (jQuery( ".main-menu ul:first li:first a:first-child" ).is(":focus")) {
					marketing_agency_gotoClose = true;
				} else {
					marketing_agency_gotoClose = false;
				}
			
			}else{

			if(window.marketing_agency_responsiveMenu){
			}}}}
		}
	 	marketing_agency_checkfocusdElement();
	}
});

jQuery('document').ready(function($){
    setTimeout(function () {
		jQuery("#preloader").fadeOut("slow");
    },1000);
});

jQuery(document).ready(function(){
	jQuery(".product-cat").hide();
    jQuery("button.product-btn").click(function(){
        jQuery(".product-cat").toggle();
    });
});

jQuery(document).ready(function () {
	jQuery(window).scroll(function () {
	    if (jQuery(this).scrollTop() > 100) {
	        jQuery('.scrollup i').fadeIn();
	    } else {
	        jQuery('.scrollup i').fadeOut();
	    }
	});
	jQuery('.scrollup').click(function () {
	    jQuery("html, body").animate({
	        scrollTop: 0
	    }, 600);
	    return false;
	});
});