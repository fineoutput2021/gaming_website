// $('#registerLink').click(function(){$('#register').show();$('#loginfrm').hide();$('#recover').hide();}) 
// $('#loginLink').click(function(){$('#loginfrm').show();$('#register').hide();$('#recover').hide();})
$("#recoverLink").click(function(){$('#recover').show();$('#loginfrm').hide();})
 $('#loginLink2').click(function(){$('#loginfrm').show();$('#recover').hide();})
// $(document).bind("contextmenu",function(e) {e.preventDefault();});
/*
window.oncontextmenu = function () {
   return false;
}
if($.browser.chrome) {
document.onkeydown = function (e) { 
    // if (window.event.keyCode == 123 ||  e.button==2 ||e.shiftKey || e.ctrlKey)    
    if (window.event.keyCode == 123 ||  e.button==2 ||e.ctrlKey)    
    return false;
}
}
*/
$("#signin").validate({ rules:{ log_mail: {  required: true,email: true }, log_pwd: { required: true } }, messages: { log_pwd: { required: "Please provide a password" },log_mail: "Please enter a valid email address" } });
$("#recoverFrm").validate({ rules:{ mailId: {  required: true,email: true } } });


	