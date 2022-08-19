  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <div class="content-wrapper">
          <section class="content-header">
             <h1>
            Profile
            </h1>
            <ol class="breadcrumb">
             <li><a href="<?php echo base_url() ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
              <li><a href="<?php echo base_url() ?>admin/college"><i class="fa fa-dashboard"></i> All Profile </a></li>

            </ol>
          </section>
  		<section class="content">
  		<div class="row">
         <div class="col-lg-12">

                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h3 class="panel-title"><i class="fa fa-money fa-fw"></i>Profile</h3>
                              </div>
                              <div class="panel-body">
                                  <div class="col-lg-10">

                                  <div class="table-responsive">
                                      <table class="table table-hover">


<?php $i=1; foreach($profile_data->result() as $data) { ?>


  											<tr>
  <td> <strong>Name</strong>  <span style="color:red;">*</span></strong> </td>
                      <td>
  								<?php echo $data->name ?>
  	                         </td>
      										</tr>
                          <tr>
    <td> <strong>Email</strong>  <span style="color:red;">*</span></strong> </td>
                        <td>
                    <?php echo $data->email ?>
                               </td>
                            </tr>
                            <tr>
      <td> <strong>Phone</strong>  <span style="color:red;">*</span></strong> </td>
                          <td>
                      <?php echo $data->phone ?>
                                 </td>
                              </tr>
                              <tr>
        <td> <strong>Address</strong>  <span style="color:red;">*</span></strong> </td>
                            <td>
                        <?php echo $data->address ?>
                                   </td>
                                </tr>
                                <tr>
          <td> <strong>Password</strong>  <span style="color:red;">*</span></strong> </td>
                              <td>
<button type="button" class="btn btn-default" id="popcustom" pd-popup-open="popupNew" name="button">Change password</button>


<div class="popup" pd-popup="popupNew">
    <div class="popup-inner">
        <h1>Please Enter old and new password</h1>
        <div id="result"></div>
<br />
<br />
<form method="post" id="searchForm">


      <p><div class="form-group">

  <input type="password" class="form-control" name="old" placeholder="Enter Old Password" required>
</div>
<div class="form-group">

  <input type="password" class="form-control" name="new" placeholder="Enter New Password" required>
</div>
</p>
  <p><button type="submit"  class="btn btn-success" >Change</button></p>
  </form>
        <p><a pd-popup-close="popupNew"  class="btn btn-danger">Close</a></p>
        <a class="popup-close" pd-popup-close="popupNew"> </a>
    </div>
</div>


                                     </td>
                                  </tr>
                                <tr class="st1">
                                <td> <strong>Image</strong> </td>
                                <td>
                                    <?php if($data->image!=""){  ?>
              <img id="slide_img_path" height=50 width=100  src="<?php echo base_url()."assets/uploads/team/".$data->image ?>" >
                                <?php }else {  ?>
                                Sorry No image Found
                                <?php } ?>
                                  </td>
                                  </tr>
                          <tr>
    												<td colspan="2" >
    													<a href="<?=base_url()?>dcadmin/Home"><input type="submit" class="btn btn-default" value="save"></a>
    												</td>
    											</tr>


                                        </table>
                                        <?php $i++; } ?>
                                    </div>



                                    </div>

                                </div>

                            </div>

                        </div>
                        </div>

            </section>
          </div>

    	  <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->
    	  	<script type="text/javascript" src="<?php echo base_url() ?>assets/slider/ajaxupload.3.5.js"></script>
          <script>
          // Attach a submit handler to the form
          $( "#searchForm" ).submit(function( event ) {

            // Stop form from submitting normally
            event.preventDefault();

            // Get some values from elements on the page:
            var $form = $( this ),
              old = $form.find( "input[name='old']" ).val(),
              newpass = $form.find( "input[name='new']" ).val()
            //   url = $form.attr( "action" );

            // Send the data using post
            // var posting = $.post( url, { s: term } );
            $.ajax({
                       type: "POST",
                       url: "<?php echo base_url(); ?>dcadmin/System/change_pass",
                       data: {
                          'old': old,
                          'new': newpass
                        },
                       success: function(server_response){
                                        // if(server_response == 'success'){
                                             $("#result").html(server_response);
                                        // }
                                        // else{
                                             // alert('Not OKay');
                                            // }

                                 }
             });   //$.ajax ends here
            // Put the results in a div
            // posting.done(function( data ) {
            //
            //
            //
            //   var content = $( data ).find( "#content" );
            //   // console.log(content)
            //     alert(JSON.stringify(content));
            //   $( "#result" ).empty().append( content );
            // });
          });
          </script>

    <!-- <script type="text/javascript" src="<?php echo base_url() ?>assets/slider/ajaxupload.3.5.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/slider/rs.js"></script>	  -->
