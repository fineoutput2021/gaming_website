<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Update Game Features
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url() ?>dcadmin/Home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url() ?>dcadmin/Features/view_features"><i class="fa fa-undo" aria-hidden="true"></i> View Game Features</a></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-lg-12">

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Update Game Features</h3>
          </div>

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
            <div class="col-lg-10">
              <form action="<?php echo base_url() ?>dcadmin/Features/update_feature_data/<?=$id?>" method="POST" id="slide_frm" enctype="multipart/form-data">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <tr>
                      <td> <strong>Round</strong> <span style="color:red;">*</span></strong> </td>
                      <td>
                        <input type="text" name="round" class="form-control" placeholder="" required value="<?=$features_data->round?>" />
                      </td>
                    </tr>
                    <tr>
                      <td> <strong>Step</strong> <span style="color:red;">*</span></strong> </td>
                      <td>
                        <input type="text" name="step" class="form-control" placeholder="" required value="<?=$features_data->step?>" />
                      </td>
                    </tr>
                    <tr>
                      <td> <strong>Offer</strong> <span style="color:red;">*</span></strong> </td>
                      <td>
                        <input type="text" name="offer" class="form-control" placeholder="" required value="<?=$features_data->offer?>" />
                      </td>
                    </tr>
                    <tr>
                      <td> <strong>Title</strong> <span style="color:red;">*</span></strong> </td>
                      <td>
                        <input type="text" name="title" class="form-control" placeholder="" readonly required value="<?=$features_data->title?>" />
                      </td>
                    </tr>
                    <tr>
                      <td> <strong>Inflow</strong> </td>
                      <td>
                        <input type="text" name="inflow" class="form-control" placeholder="" value="<?=$features_data->inflow?>" />
                      </td>
                    </tr>
                    <tr>
                      <td> <strong>Outflow</strong> </td>
                      <td>
                        <input type="text" name="outflow" class="form-control" placeholder="" value="<?=$features_data->outflow?>" />
                      </td>
                    </tr>
                    <tr>
                      <td> <strong>Message1</strong> </td>
                      <td>
                        <input type="text" name="msg1" class="form-control" placeholder="" value="<?=$features_data->msg1?>" />
                      </td>
                    </tr>
                    <tr>
                      <td> <strong>Message2</strong> </td>
                      <td>
                        <input type="text" name="msg2" class="form-control" placeholder="" value="<?=$features_data->msg2?>" />
                      </td>
                    </tr>
                    <tr>
                      <td> <strong>Message3</strong></td>
                      <td>
                        <input type="text" name="msg3" class="form-control" placeholder="" value="<?=$features_data->msg3?>" />
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <input type="submit" class="btn btn-success" value="save">
                      </td>
                    </tr>
                  </table>
                </div>

              </form>

            </div>



          </div>

        </div>

      </div>
    </div>
  </section>
</div>


<script type="text/javascript" src="<?php echo base_url() ?>assets/slider/ajaxupload.3.5.js"></script>
<link href="<?php echo base_url() ?>assets/cowadmin/css/jqvmap.css" rel='stylesheet' type='text/css' />
