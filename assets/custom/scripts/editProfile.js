	 /**

 Main JavaScript file for edit profile  
 Date : 15 July 2015
 
 Author : Rishi Shrivastava
 
 */
	 $("#country").change(function(){
	 var country_id = $(this).val();
		if(country_id=="")
		{
			return false;
		}
		else
		{
			var u_rl= base_url+"admin/profile/getState/"+country_id;
            var data = ""; 
             var opton="<option value=''>Please Select </option>";
            $('#state option').remove();
            doAjaxCall(u_rl,data,"POST",false,function(html){
			if(html!="NA")
				{
					var s = jQuery.parseJSON(html);
					$.each(s, function(i) {
					opton +='<option value="'+s[i]['state_id']+'">'+s[i]['state_name']+'</option>';
					});
					$('#state').append(opton);
					$('#city').append("<option value=''>Please Select State</option>");
				}
				else
				{
					alert('No Location Found');
					return false;
				}
			
			}); 
		}	
 });
// for city
 $("#state").change(function(){
	 var state_id = $(this).val();
		if(state_id=="")
		{
			return false;
		}
		else
		{
			var u_rl= base_url+"admin/profile/getCity/"+state_id;
            var data = ""; 
             var opton="<option value=''>Please Select </option>";
            $('#city option').remove();
            doAjaxCall(u_rl,data,"POST",false,function(html){
			if(html!="NA")
				{
					var s = jQuery.parseJSON(html);
					$.each(s, function(i) {
					opton +='<option value="'+s[i]['city_id']+'">'+s[i]['city']+'</option>';
					});
					$('#city').append(opton);
					//$('#city').append("<option value=''>Please Select State</option>");
				}
				else
				{
					alert('No Location Found');
					return false;
				}
			
			}); 
		}	
 }); 