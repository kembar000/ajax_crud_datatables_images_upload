<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Kodinger">
	<title>My Login Page &mdash; Bootstrap 4 Login Page Snippet</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/loginluar/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/loginluar/css/my-login.css">
</head>
<body class="my-login-page">
	<section class="h-100">

		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div class="card-wrapper">
					<div class="brand">
						<img src="<?php echo base_url();?>assets/loginluar/img/logo.jpg">
					</div>
					<div class="card fat">
						<div class="card-body">
							<h4 class="card-title">Login</h4>

							<form method="POST" action="<?php echo site_url('login/proses')?>">
								<?php
						        	if (validation_errors() || $this->session->flashdata('result_login')) {
						        ?>
						        <div class="alert alert-error">
						        	<button type="button" class="close" data-dismiss="alert">&times;</button>
						            <strong>Warning!</strong>
						            <?php echo validation_errors(); ?>
						            <?php echo $this->session->flashdata('result_login'); ?>
						        </div>    
						        <?php } ?>
								<div class="form-group">
									<label for="email">E-Mail Address</label>
									<input id="email" type="email" class="form-control" name="email" value="<?php echo set_value('email'); ?>" required autofocus>
								</div>

								<div class="form-group">
									<label for="password">Password</label>
									<input id="pass" type="password" class="form-control" name="pass" value="<?php echo set_value('pass'); ?>" required data-eye>
								</div>

								<div class="form-group">
									<label>
										<input type="checkbox" name="remember"> Remember Me
									</label>
								</div>

								<div class="form-group no-margin">
									<button type="submit" class="btn btn-primary btn-block">
										Login
									</button>
								</div>
								<div class="margin-top20 text-center">
									Don't have an account? <a href="register.html">Create One</a>
								</div>
							</form>
						</div>
					</div>
					<div class="footer">
						Copyright &copy; Your Company 2017
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="<?php echo base_url();?>assets/loginluar/js/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="<?php echo base_url();?>assets/loginluar/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>assets/loginluar/js/my-login.js"></script>
</body>
</html>