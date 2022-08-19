<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="<?php echo base_url() ?>assets/admin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/knob/jquery.knob.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src='<?php echo base_url() ?>assets/admin/plugins/fastclick/fastclick.min.js'></script>
<script src="<?php echo base_url() ?>assets/admin/dist/js/app.min.js" type="text/javascript"></script>
<!-- <script src="<?php echo base_url() ?>assets/admin/dist/js/demo.js" type="text/javascript"></script> -->
    <script>
    $(function() {
        //----- OPEN
        $('[pd-popup-open]').on('click', function(e)  {
            var targeted_popup_class = jQuery(this).attr('pd-popup-open');
            $('[pd-popup="' + targeted_popup_class + '"]').fadeIn(100);
            // display: inline-block;
            //$(".popup").css("display", "block");
            // alert('hii');
            e.preventDefault();
        });

        //----- CLOSE
        $('[pd-popup-close]').on('click', function(e)  {
            var targeted_popup_class = jQuery(this).attr('pd-popup-close');
            $('[pd-popup="' + targeted_popup_class + '"]').fadeOut(200);

            e.preventDefault();
        });
    });

    </script>

<script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/slider/ajaxupload.3.5.js"></script>
          <script>
          // Attach a submit handler to the form
          $(document).ready(function(){
            $('#userTable').dataTable({
              responsive: true,
              "bStateSave": true,
              "fnStateSave": function (oSettings, oData) {
                  localStorage.setItem('offersDataTables', JSON.stringify(oData));
              },
              "fnStateLoad": function (oSettings) {
                  return JSON.parse(localStorage.getItem('offersDataTables'));
              }
            });
               // alert('OKay');
      $("#msgid9").one('click', function() {

          $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>admin/messages/seen",
          success: function(server_response){
                if(server_response == 'success'){
                     // $("#result").html(server_response);
                     alert('OKay');
                     // $('#msgid8').removeClass('label label-success');
                     $('span[id^="msgid8"]').remove();
                     // $('#msgid9').removeClass('onion').addClass('onionClick');
                }
                else{
                     alert('Not OKay');
                    }

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
          });
          });
          </script>
</script>
  </body>
</html>
