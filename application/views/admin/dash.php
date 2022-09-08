<?php
$no_of_cases = $this->db->get_where('tbl_game_cases', array('action is NOT NULL'=> null, false))->num_rows();
$no_of_winner = $this->db->get_where('tbl_game_cases', array('status'=> 'winner'))->num_rows();
$no_of_losser = $this->db->get_where('tbl_game_cases', array('status'=> 'losser'))->num_rows();
// $winner_percetage= $no_of_winner * 100 /$no_of_cases;
// $losser_percetage= $no_of_losser * 100 /$no_of_cases;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
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
  <section class="content-header">
    <h1>
      Welcome to Life Changer
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Info boxes -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-list"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Cases</span>
            <span class="info-box-number"><?=$no_of_cases?></span>
          </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
      </div><!-- /.col -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-trophy"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Winners</span>
            <span class="info-box-number"><?=$no_of_winner?></span>
          </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
      </div><!-- /.col -->

      <!-- fix for small devices only -->
      <div class="clearfix visible-sm-block"></div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Lossers</span>
            <span class="info-box-number"><?=$no_of_losser?></span>
          </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
      </div><!-- /.col -->
    </div><!-- /.col -->
    <div class="col-md-12 col-sm-12 col-xs-12 text-center" style="margin-top:5rem">
      <a href="<?php echo base_url() ?>dcadmin/Play/play"><button type="button" class="btn custom_btn">Play Game</button></a>
    </div>
</div><!-- /.row -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
</div><!-- ./wrapper -->
