<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Showimg Results for <?=$string?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url() ?>dcadmin/Home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url() ?>dcadmin/Play/view_results"><i class="fa fa-undo" aria-hidden="true"></i> View   Game Results </a></li>
      <!-- <li class="active"></li> -->
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <!-- <a class="btn custom_btn" href="<?php echo base_url() ?>dcadmin/banner/add_banner" role="button" style="margin-bottom:12px;"> Add banner</a> -->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-money fa-fw"></i>View Search Results</h3>
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
              <div style="display:flex;justify-content:end">
                <form action="<?=base_url()?>dcadmin/Play/search" method="get">
                  <input name="string" class="form-control" value="<?=$string?>" placeholder="search" style="margin-bottom: 5px;display: inline-flex;width: 71%;"/>
                  <button type="submit" class="btn custom_btn">Search</button>
                </form>
              </div>
              <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-hover table-striped" >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Step History</th>
                      <th>Round</th>
                      <th>Step</th>
                      <th>Title</th>
                      <th>Action</th>
                      <th>Status</th>
                      <th>Salary</th>
                      <th>Cash In Hand</th>
                      <th>Personal Expense</th>
                      <th>Housing Expense</th>
                      <th>Business Expense</th>
                      <th>Passive Income</th>
                      <th>Buy</th>
                      <th>Sell</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i=1;foreach ($game_data->result() as $data) {
                    $step_info = $this->db->get_where('tbl_features', array('round'=> $data->round_id,'step'=> $data->step_id))->result();
                    $setting_info = $this->db->get_where('tbl_setting')->result();
                    $buy = json_decode($data->buy);
                    $sell = json_decode($data->sell);
                    ?>
                    <tr>
                      <td><?php echo $i ?> </td>
                      <td><?php echo $data->summary ?> </td>
                      <td><?php echo $data->round_id?> </td>
                      <td><?php echo $data->step_id?> </td>
                      <td><?php echo $step_info[0]->title?> </td>
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
                      <td>₹<?php echo $data->personal_exp?> </td>
                      <td>₹<?php echo $data->loan_exp-$setting_info[0]->loan_exp?> </td>
                      <td>₹<?php echo $setting_info[0]->loan_exp?> </td>
                      <td>₹<?php if (!empty($data->passive_income)) {
                          echo $data->passive_income;
                      } else {
                          echo 0;
                      } ?> </td>
                      <td><?php
                      if (!empty($buy)) {
                          foreach ($buy as $key) {
                              if ($key==1) {
                                  echo 'TCS Stock,';
                              } elseif ($key==2) {
                                  echo 'Youtube Channel,';
                              } elseif ($key==3) {
                                  echo 'Real Estate,';
                              } elseif ($key==4) {
                                  echo 'Factory Setup,';
                              } elseif ($key==5) {
                                  echo 'Commercial Setup,';
                              } elseif ($key==6) {
                                  echo 'Asian Paint Stock,';
                              }elseif ($key==7) {
                                  echo 'Diagnostic Lab,';
                              }elseif ($key==8) {
                                  echo 'Realiance Stock,';
                              }elseif ($key==9) {
                                  echo 'Land,';
                              }
                          }
                      } ?> </td>
                      <td><?php
                      if (!empty($sell)) {
                          foreach ($sell as $key) {
                              if ($key==1) {
                                  echo 'Youtube Channel,';
                              } elseif ($key==2) {
                                  echo 'TCS Stock,';
                              } elseif ($key==3) {
                                  echo 'Asian Paint Stock,';
                              } elseif ($key==4) {
                                  echo 'Commercial Setup,';
                              } elseif ($key==5) {
                                  echo 'Land,';
                              } elseif ($key==6) {
                                  echo 'Diagnostic Lab,';
                              }
                          }
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
