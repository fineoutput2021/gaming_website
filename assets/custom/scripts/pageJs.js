/**

 Main JavaScript file for Page File  
 Date : 16 July 2015
 
 Author : Rishi Shrivastava
 
 */
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

$(document).ready(function(){
	
	$("#page_title").blur(function(){
		var slug_val=$("#page_slug").val();
		if(slug_val=="")
		{
			alert("Please fill the page name again ");
			return false;
		}
		else
		{
			var u_rl= base_url+"admin/pages/getData/page_detail";
            var data= {"page_slug":slug_val};
             var opton="";
            // $('#state option').remove();
            doAjaxCall(u_rl,data,"POST",false,function(html){
			if(html!="NA")
				{
					var s = jQuery.parseJSON(html);
					alert("Please change the name or slug of page due to duplicate name of pages");
					$("#page_slug").removeAttr("readonly");
					return false;
				}
			}); 
		}	
	})
	
	$("#page_slug").blur(function(){
		var slug_val=$("#page_slug").val();
		if(slug_val=="")
		{
			alert("Please fill the page name again ");
			return false;
		}
		else
		{
			var u_rl= base_url+"admin/pages/getData/page_detail";
            var data= {"page_slug":slug_val};
             var opton="";
            // $('#state option').remove();
            doAjaxCall(u_rl,data,"POST",false,function(html){
			if(html!="NA")
				{
					var s = jQuery.parseJSON(html);
					alert("Please change the name or slug of page due to duplicate name of pages");
					$("#page_slug").removeAttr("readonly");
					return false;
				}else{
					$("#page_slug").attr("readonly","readonly");
				}
			}); 
		}	
	})
	
	$("#page_style").change(function(){
		var p_s=$(this).val();
		if(p_s==0){
			$("#styleDiv").hide();
			  $('#sidebar_id option').remove();
		return false;		
		}else{
			var u_rl= base_url+"admin/pages/getData/sidebar_detail";
            var data= {"sidebar_position":p_s,"is_active":1};
             var opton="";
             $('#sidebar_id option').remove();
            doAjaxCall(u_rl,data,"POST",false,function(html){
			if(html!="NA")
				{
					var s = jQuery.parseJSON(html);
					$.each(s, function(i) {
					opton +='<option value="'+s[i]['sidebar_id']+'">'+s[i]['sidebar_name']+'</option>';
					});
					$('#sidebar_id').append(opton);
					$("#styleDiv").show();
				}else{
					alert("Sorry No Sidebar available Now Please first create sidebar  ");
					return false;
				}
			}); 
			
		}
		
	})
	
		
	$("#get_post").click(function(){
		$s=$(this).prop("checked");
		
		
		if($s)
		{
			var u_rl= base_url+"admin/pages/getData/post_category";
            var data= {"is_active":1};
             var opton="";
             $('#post_category option').remove();
            doAjaxCall(u_rl,data,"POST",false,function(html){
			if(html!="NA")
				{
					var s = jQuery.parseJSON(html);
					$.each(s, function(i) {
					opton +='<option value="'+s[i]['post_category_id']+'">'+s[i]['category_name']+'</option>';
					});
					$('#post_category').append(opton);
					$("#postDiv").show();
				}else{
					alert("Sorry No Sidebar available Now Please first create sidebar  ");
					return false;
				}
			}); 
		}
		else
		{
			$("#postDiv").hide();
			
			$('#post_category option').remove();
		}
		
	})
	
	
	var btnUploadimage=$('#income_frm_rent_file');
		var namefileimage=$('#income_frm_rent_file').attr('name');
		new AjaxUpload(btnUploadimage, {
		    action: base_url+"admin/pages/image_upload/"+namefileimage,
            name:namefileimage,
            onSubmit: function(file, ext){
                if (! (ext && /^(jpg|png|jpeg|gif|doc|docx|xls|xlsx|ppt|txt|pdf)$/.test(ext))){ 
                    // extension is not allowed 
			status.text('Only JPG, PNG or GIF files are allowed');
                    return false;
                }
				//$("#login_img").show();
            },
            onComplete: function(file, response){
				//$("#login_img").hide();
                if(response!="error"){
        			 var obj = JSON.parse(response);
					//var obj=response;
					console.log(obj);
					console.log(obj['orig_name']);
					$("#page_image").val(obj['orig_name']);
					$("#slide_img_path").attr("src",base_url+obj['new_name']);
					$("#slide_img_path").attr("height",50);
					$("#slide_img_path").attr("width",100);
					$("#slide_img_path").show();
					//$("#income_frm_rent_file").hide();
                } else{
                    //$('<li></li>').appendTo('#files').text(file).addClass('error');
                }
            }
        });
		
		
		
})