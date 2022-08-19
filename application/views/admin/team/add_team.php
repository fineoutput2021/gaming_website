<div class="content-wrapper">
        <section class="content-header">
           <h1>
          Add New Team
          </h1>
          <ol class="breadcrumb">
           <li><a href="<?php echo base_url() ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url() ?>admin/college"><i class="fa fa-dashboard"></i> All Team </a></li>

          </ol>
        </section>
		<section class="content">
		<div class="row">
       <div class="col-lg-12">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Add New Team</h3>
                            </div>
                            <div class="panel-body">
                    		<? if(!empty($this->session->flashdata('smessage'))){ ?>
                        <div class="alert alert-success alert-dismissible fade in">
                          <strong><? echo $this->session->flashdata('smessage'); ?></strong>
                        </div>
                      <? }
                       if(!empty($this->session->flashdata('emessage'))){ ?>
                      <div class="alert alert-danger alert-dismissible fade in">
                        <strong><? echo $this->session->flashdata('emessage'); ?></strong>
                      </div>
                    <? } ?>
                                <div class="col-lg-10">
                                   <form action="<?php echo base_url() ?>dcadmin/system/add_team_data" method="POST" id="slide_frm" enctype="multipart/form-data">
                                <div class="table-responsive">
                                    <table class="table table-hover">

											<tr>
                                                <td> <strong>Name</strong>  <span style="color:red;">*</span></strong> </td>
                                                <td>
													<input type="text" name="name"  class="form-control" placeholder="" required value="" />
	                                            </td>
    										</tr>
												<tr>
    <td> <strong>Email</strong>  <span style="color:red;">*</span></strong> </td>
    <td>

					<input type="email" name="email"  class="form-control" placeholder="" required value="" />
	                             </td>
    										</tr>
                        <tr>
    <td> <strong>Phone (optional)</strong>  </strong> </td>
    <td>

					<input type="text" name="phone"  class="form-control" placeholder="" value="" />
	                             </td>
    										</tr>
                        <tr>
    <td> <strong>Address (optional)</strong> </strong> </td>
    <td>

          <input type="text" name="address"  class="form-control" placeholder="" value="" />
                               </td>
                        </tr>
                        <tr>
    <td> <strong>Password</strong>  <span style="color:red;">*</span></strong> </td>
    <td>

          <input type="text" name="password"  class="form-control" placeholder="" required value="" />
                               </td>
                        </tr>
                        <tr>
                  <td> <strong>Permission Level</strong>  <span style="color:red;">*</span></strong> </td>
                  <td>
            <div class="form-group">

  <select class="form-control" name="power" required>
      <option value=1">Please select Type</option>
    <option value="1">Super Admin</option>
    <option value="2">Admin</option>
    <option value="3">Manager</option>

  </select>
</div>
</td>
</tr>

<tr>
<td> <strong>services</strong>  <span style="color:red;">*</span></strong> </td>
<td>
<div class="form-group">
  <div class="checkbox">
    <label><input type="checkbox" name="service" value="999">All</label>
  </div>
  <? foreach ($side->result() as $s) {
    ?>
    <div class="checkbox">
      <label><input type="checkbox" name="services[]" value="<? echo $s->id; ?>"><? echo $s->name; ?></label>
    </div>
    <?
  } ?>

</div>
</td>
</tr>


											<tr>
												<td> <strong>Image</strong> </td>
												<td>


													<input type="file" name="fileToUpload1"></input>

														</div>
												</td>
											</tr>


											<tr>
												<td colspan="2" >
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
				<link href="<? echo base_url() ?>assets/cowadmin/css/jqvmap.css" rel='stylesheet' type='text/css' />



<style>

</style>
<!-- <script type="text/javascript" src="<?php echo base_url() ?>assets/slider/ajaxupload.3.5.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/slider/rs.js"></script>	  -->
