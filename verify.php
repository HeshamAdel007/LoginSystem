<?php

if(isset($_POST['submit'])) {

  // Getting Value From Form

  $token = $_POST['token'];

  // Database Conections

	$link	 = mysqli_connect('localhost','heshamadel','123123','login_system') or die ("could not connect to mysql");	
  $fire  = mysqli_query($link,"select * from users where token='$token'");
  $row   = mysqli_fetch_array($fire);
  $count = mysqli_num_rows($fire);

  if( $count > 0 ) {
    $email = $row['email'];
    mysqli_query($link,"update users set status=1,token='' where email='$email'");
    $msg = "Your account is verified now.<a href='login.php'>Click Here</a> to login";
  }
  else {
    $msg="Email verification code not match.<br><a href='resent.php'>Re-send Code</a>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Email Verification </title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	</head>
	<body>
		<div class="container">
			<div class="row mt-1">
				<div class="col-sm-3"></div>
				<div class="col-sm-6">
				<?php 
					//setting Error Message Here
					if(isset($msg)): ?>
						<div class="alert alert-warning" role="alert">
							<strong>Alert!</strong> <?php echo $msg; ?>
						</div>
					<?php endif; ?>
					<div class="card text-center">
						<div class="card-header text-white bg-primary">
              Verification
						</div>
						<div class="card-body">
							<form action="" method="post">
								<div class="form-group">
									<input 
										type="text" 
										name="token" 
										class="form-control" 
										placeholder="Enter Your Verification Code">
								</div>
								<div class="form-group">
									<input 
                    name="submit" 
                    type="submit" 
                    class="btn btn-primary"
                    value="Verify">
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-sm-3"></div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	</body>
</html>
