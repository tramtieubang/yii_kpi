/**
 * ham hien thi notify
 * tham so truyen vao: text can hien thi
 * [su dung trong ajax crud]
 */
function showNotif(mess){
    return $.growl({
        style: "notice",
        title: "Thông báo!",
        duration: 2000,
        message: mess           
    });
}
/**
 * in html tu 1 div
 * [them id #print cho div parent]
 */
function printQr(){
	$('#print').printThis({
		 //debug: false,               // show the iframe for debugging
		 importCSS: false,            // import parent page css
		 loadCSS: ['/css/print-single.css?v=25'],
		 //printDelay: 333,
    });
}

/**
 * in html tu 1 div
 * [them id #print cho div parent]
 */
function printPhieu(){
	$('#print').printThis({
		 //debug: false,               // show the iframe for debugging
		 importCSS: false,            // import parent page css
		 loadCSS: ['/css/print-phieu.css?v=2'],
		 //printDelay: 333,
    });
}


/**
 * xu ly user khong co hanh dong nao trong khoang thoi gian
 */
/*function debounce(callback, timeout, _this) {
    var timer;
    return function(e) {
        var _that = this;
        if (timer)
            clearTimeout(timer);
        timer = setTimeout(function() { 
            callback.call(_this || _that, e);
        }, timeout);
    }
}

// we'll attach the function created by "debounce" to each of the target
// user input events; this function only fires once 2 seconds have passed
// with no additional input; it can be attached to any number of desired
// events
var userAction = debounce(function(e) {
    console.log("silence");
}, 2000);

document.addEventListener("mousemove", userAction, false);
document.addEventListener("click", userAction, false);
document.addEventListener("scroll", userAction, false);*/


function setMenuActive(){
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	const dataMenu = urlParams.get('menu');
	//console.log(dataMenu);
	if(dataMenu != null){
		const parentMenu = dataMenu.substring(0,dataMenu.length - 1);
		//console.log(parentMenu);
		$("a[data-menu='"+dataMenu+"']").addClass('active');
		$("ul[data-menu='"+parentMenu+"']").css({ display: "block" });
	}
}
setMenuActive();

function toggleSearch(){
	$("#divFilterExtend").toggle();
}