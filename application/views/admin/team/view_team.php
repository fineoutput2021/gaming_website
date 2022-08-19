<div class="content-wrapper">
        <section class="content-header">
           <h1>
        Team
          </h1>
          <ol class="breadcrumb">
           <li><a href="<?php echo base_url() ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url() ?>admin/college"><i class="fa fa-dashboard"></i> All Team </a></li>
            <li class="active">View Team</li>
          </ol>
        </section>
		<section class="content">
		<div class="row">
       <div class="col-lg-12">
				   <a class="btn btn-info cticket" href="<?php echo base_url() ?>dcadmin/system/add_team" role="button" style="margin-bottom:12px;"> Add Team</a>
                        <div class="panel panel-default">
                  <? if(!empty($this->session->flashdata('smessage'))){ ?>
                        <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  <? echo $this->session->flashdata('smessage'); ?>
                  </div>
                    <? }
                     if(!empty($this->session->flashdata('emessage'))){ ?>
                     <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                <? echo $this->session->flashdata('emessage'); ?>
                </div>
                  <? } ?>
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i>View team</h3>
                            </div>
                               <div class="panel panel-default">

                            <div class="panel-body">
                                <div class="">
                                    <table class="table table-bordered table-hover table-striped" id="userTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Position</th>
                                                <th>Permissions</th>
                        												<th>Images</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php $i=1; foreach($team_data->result() as $data) { ?>
                                            <tr>
                                                <td><?php echo $i ?> </td>
                                                <td><?php echo $data->name ?></td>
                                                <td><?php echo $data->email ?></td>
                                                <td><?php echo $data->phone ?></td>
                                                <td><?php  $pos=$data->power;
                                                if($pos==1){
                                                  echo "Super Admin";
                                                }
                                                if($pos==2){
                                                  echo "Admin";
                                                }
                                                if($pos==3){
                                                  echo "Manager";
                                                }

                                                 ?></td>
                                                <td><?php $ser=json_decode($data->services);
                                                // print_r($ser);
                                                if($ser[0]=="999"){
                                                  echo "All";
                                                }
                                                else{
                                                  foreach ($ser as $key => $value) {
                                                     $val=$value;
                                                     $this->db->select('name');
                                                                 $this->db->from('tbl_admin_sidebar');
                                                                 $this->db->where('id',$val);
                                                                 $dsa= $this->db->get();
                                                                 $da=$dsa->row();
                                                                 echo $da->name;
                                                    echo "<br />";
                                                  }
                                                }


                                                ?></td>


                                                <td>
                                                    <?php if($data->image!=""){  ?>
                              <img id="slide_img_path" height=50 width=100  src="<?php echo base_url()."assets/admin/team/".$data->image ?>" >
                                                <?php }else {  ?>
                                                Sorry No image Found
                                                <?php } ?>
                                                  </td>



                                                <td><?php if($data->is_active==1){ ?>
													<p class="label pull-right bg-green" >Active</p>

											<?php } else { ?>
													<p class="label pull-right bg-yellow" >Inactive</p>


									<?php		}   ?>
												</td>
                                                <td>
												<div class="btn-group" id="btns<?php echo $i ?>">
													<div class="btn-group">
														<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> Action <span class="caret"></span></button>
													  <ul class="dropdown-menu" role="menu">

														<?php if($data->is_active==1){ ?>
														<li><a href="<?php echo base_url() ?>dcadmin/system/updateteamStatus/<?php echo base64_encode($data->id) ?>/inactive">Inactive</a></li>
														<?php } else { ?>
														<li><a href="<?php echo base_url() ?>dcadmin/system/updateteamStatus/<?php echo base64_encode($data->id) ?>/active">Active</a></li>
														<?php		}   ?>

														<li><a href="javascript:;" class="dCnf" mydata="<?php echo $i ?>">Delete User</a></li>
													  </ul>
													</div>
												</div>

													  <div style="display:none" id="cnfbox<?php echo $i ?>">
															<p> Are you sure delete this </p>
															<a href="<?php echo base_url() ?>dcadmin/system/delete_team/<?php echo base64_encode($data->id); ?>" class="btn btn-danger" >Yes</a>
															<a href="javasript:;" class="cans btn btn-default" mydatas="<?php echo $i ?>" >No</a>
													  </div>
												</td>
                                            </tr>
										<?php $i++; } ?>
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
label{
	margin:5px;
}
</style>
<script src="<?php echo base_url() ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script type="text/javascript">

 $(document).ready(function(){

$(document.body).on('click', '.dCnf', function() {
 var i=$(this).attr("mydata");
 console.log(i);

 $("#btns"+i).hide();
 $("#cnfbox"+i).show();

});

 $(document.body).on('click', '.cans', function() {
 var i=$(this).attr("mydatas");
 console.log(i);

 $("#btns"+i).show();
 $("#cnfbox"+i).hide();
})

 });

 </script>
<!-- <script type="text/javascript" src="<?php echo base_url() ?>assets/slider/ajaxupload.3.5.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/slider/rs.js"></script>	  -->
