function doAjaxCall(url,data,send_type,showLoading,callback) {
 if (showLoading) {
     $('#loading').show();
 }
 $.ajax({
     url: url,
     type: send_type,
     data: data,
     cache: false,
     success: function (html) {
     	//alert("success"+html);
         callback(html);
         if (showLoading) {
         	$('#loading').hide();
         }
     },
     error: function (html,errorThrown) {
     	//alert("error"+html);
     	//alert("error"+errorThrown);
         //console.log(html);
     }
 });
}
function show_hide(val,cond,div_class)
{

	$('#one').animate({opacity: 0.25});
if(val == cond)
		{
		
			$("."+div_class).show('fadein',function(){
					$('#one').animate({opacity: 1});
			});
		}
else
		{
		
			$("."+div_class).hide('fadeout',function(){
				$('#one').animate({opacity: 1});
			});
		}		
}

$(document).ready(function(){

$("#navigation a").each(function()
{
	if(this.href == window.location.href)
    {
    $(this).parent("li").addClass('active');
    console.log($(this).parent("li").parent("li").length);
    $(this).parents(".inner-nav").parents("li").addClass('active');
    //$(this).removeAttr('href');
    }
    else
    {
	//$(this).parent("li").removeClass('active');
    //$(this).parent("li").parents("li").removeClass('active open');
	}
});

  
})