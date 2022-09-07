<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Game Results
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url() ?>dcadmin/Home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url() ?>dcadmin/Banner/view_banner"><i class="fa fa-undo" aria-hidden="true"></i> View   Game Results </a></li>
      <!-- <li class="active"></li> -->
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <!-- <a class="btn custom_btn" href="<?php echo base_url() ?>dcadmin/banner/add_banner" role="button" style="margin-bottom:12px;"> Add banner</a> -->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-money fa-fw"></i>View   Game Results</h3>
          </div>
          <div class="panel panel-default">

            <?php if (!empty($this->session->flashdata('smessage'))) { ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4><i class="icon fa fa-check"></i> Alert!</h4>
              <?php echo $this->session->flashdata('smessage'); ?>
            </div>
            <?php }
                                               if (!empty($this->session->flashdata('emessage'))) { ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4><i class="icon fa fa-ban"></i> Alert!</h4>
              <?php echo $this->session->flashdata('emessage'); ?>
            </div>
            <?php } ?>

            <div class="panel-body">
              <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-hover table-striped" id="userTable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Step History</th>
                      <th>Round</th>
                      <th>Step</th>
                      <th>Action</th>
                      <th>Status</th>
                      <th>Salary</th>
                      <th>Cash In Hand</th>
                      <th>Expenditure</th>
                      <th>Passive Income</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i=1; foreach ($game_data->result() as $data) {
                    $history = json_decode($data->history); ?>
                    <tr>
                      <td><?php echo $i ?> </td>
                      <td><?php
                      if (!empty($history)) {
                          foreach ($history as $key) {
                              if ($key==1) {
                                  echo 'Yes,';
                              } elseif ($key==2) {
                                  echo 'No,';
                              } else {
                                  echo 'NA,';
                              }
                          }
                      } ?> </td>
                      <td><?php echo $data->round_id?> </td>
                      <td><?php echo $data->step_id?> </td>
                      <td><?php if ($data->action==1) {
                          echo 'Yes';
                      } elseif ($data->action==2) {
                          echo 'No';
                      } else {
                          echo 'NA';
                      } ?> </td>
                      <td><?php echo ucfirst($data->status)?> </td>
                      <td>₹<?php echo $data->salary?> </td>
                      <td>₹<?php echo $data->cash_in_hand?> </td>
                      <td>₹<?php echo $data->expenditure?> </td>
                      <td>₹<?php if (!empty($data->pasive_income)) {
                          echo $data->pasive_income;
                      } else {
                          echo 0;
                      } ?> </td>
                    </tr>
                    <?php $i++;
                                               } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>
</div>


<style>
  label {
    margin: 5px;
  }
</style>
<script src="<?php echo base_url() ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script type="text/javascript">
  $(document).ready(function() {


    $(document.body).on('click', '.dCnf', function() {
      var i = $(this).attr("mydata");
      console.log(i);

      $("#btns" + i).hide();
      $("#cnfbox" + i).show();

    });

    $(document.body).on('click', '.cans', function() {
      var i = $(this).attr("mydatas");
      console.log(i);

      $("#btns" + i).show();
      $("#cnfbox" + i).hide();
    })

  });
</script>
<!-- <script type="text/javascript" src="<?php echo base_url() ?>assets/banner/ajaxupload.3.5.js"></script>
      <script type="text/javascript" src="<?php echo base_url() ?>assets/banner/rs.js"></script>	  -->
