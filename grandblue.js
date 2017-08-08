function isVaild(){
	
	var password = document.getElementById('password').value;
	if (password == "123"){
		window.open("https://www.w3schools.com");
	}
	else{
		window.open("https://www.w3schools.com");
	}
	
}

function myFunction() {
    window.open("https://www.w3schools.com");
}

$(function(){
    $("#gotop").click(function(){
        jQuery("html,body").animate({
            scrollTop:0
        },1000);
    });
    $(window).scroll(function() {
        if ( $(this).scrollTop() > 300){
            $('#gotop').fadeIn("fast");
        } else {
            $('#gotop').stop().fadeOut("fast");
        }
    });
});