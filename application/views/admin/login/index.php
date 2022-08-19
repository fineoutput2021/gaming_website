<!DOCTYPE HTML>
<html lang="zxx">

<head>
	<title>Login|<?=SITE_NAME;?></title>
	<!-- Meta tag Keywords -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="" />
	<script>
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<!-- Meta tag Keywords -->
	<!-- css files -->
	<link rel="stylesheet" href="<? echo base_url() ?>assets/admin/login/css/style.css" type="text/css" media="all" />
	<!-- Style-CSS -->
	<link rel="stylesheet" href="<? echo base_url() ?>assets/admin/login/css/font-awesome.css">
	<!-- Font-Awesome-Icons-CSS -->
	<!-- //css files -->
	<!-- web-fonts -->
	<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
	<!-- //web-fonts -->
</head>

<body>
	<div class="video-w3l" data-vide-bg="<? echo base_url() ?>assets/admin/login/video/1">
		<!--header-->
		<div class="header-w3l">
			<h1>
				<span><?=SITE_NAME;?></span>
				<span>L</span>ogin

			</h1>
		</div>
		<!--//header-->
		<div class="main-content-agile">
			<div class="sub-main-w3">
				<h2>Login Here
					<i class="fa fa-hand-o-down" aria-hidden="true"></i>
				</h2>
						<? if(!empty($this->session->flashdata('smessage'))){ ?>
				    <div class="alert alert-success">
				      <strong><? echo $this->session->flashdata('smessage'); ?></strong>
				    </div>
				  <? }
				   if(!empty($this->session->flashdata('emessage'))){ ?>
				  <div class="alert alert-danger">
				    <strong><? echo $this->session->flashdata('emessage'); ?></strong>
				  </div>
				<? } ?>
				<form action="<? echo base_url() ?>login/admin_login_process" method="post">
					<div class="pom-agile">
						<span class="fa fa-user-o" aria-hidden="true"></span>
						<input placeholder="Username" name="email" class="user" type="email" required="">
					</div>
					<div class="pom-agile">
						<span class="fa fa-key" aria-hidden="true"></span>
						<input placeholder="Password" name="password" class="pass" type="password" required="">
					</div>
					<div class="sub-w3l">

						<!-- <a href="#" id="butpas">Forgot Password?</a> -->
						<div class="clear"></div>
					</div>
					<div class="right-w3l">
						<input type="submit" value="Login">
					</div>
					</form>
				<br />
						<form action="<? echo base_url() ?>login/admin_login_reset" method="post">
<div id="passrst1" style="display:none;">
					<div class="pom-agile" >
						<span class="fa fa-user-o" aria-hidden="true"></span>
						<input placeholder="Enter Email to reset password" name="email" class="user" type="email" required="">
					</div>
					<div class="right-w3l">
						<input type="submit" value="Reset">
					</div>
				</div>
				</form>
			</div>
		</div>
		<!--//main-->
		<!--footer-->

		<!--//footer-->
	</div>

	<!-- js -->
	<script src="<? echo base_url() ?>assets/admin/login/js/jquery-2.1.4.min.js"></script>
	<script src="<? echo base_url() ?>assets/admin/login/js/jquery.vide.min.js"></script>
	<!-- //js -->
<script>
$(document).ready(function(){
$("#butpas").click(function(){
    $("#passrst1").toggle();
		// alert('hi');
});
});
</script>
</body>

</html>
