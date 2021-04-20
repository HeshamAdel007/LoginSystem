<?php 

if (isset($_POST['submit'])) {
	// Getting Value From Form
	$email 		= $_POST['email'];
	$password = $_POST['password'];

	// Database Conections
	$link	 = mysqli_connect('localhost','heshamadel','123123','login_system') or die ("could not connect to mysql");
	$query = mysqli_query($link,"select * From users Where email='$email'");
	$row   = mysqli_fetch_assoc($query);
	$count = mysqli_num_rows($query);

	if( $count > 0 ){

		if ( $row['status']=='1' ) {
			
			if ( $row['password']==$password ) {
				session_start();
				$_SESSION['email']=$email;
				header('location: home.php');
			} else {
				$msg = "Password  Incorect. Try again";
			}
			
		} else {
			$msg = "Your Account is not verified yet.<a href='verify.php'>Click Here</a> to verify";
		}

	} else {
		$msg = "Your Email id does not match.<br><a href='index.php'>Click Here</a> for register";
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
							Login Form
						</div>
						<div class="card-body">
							<form action="" method="post">
								<div class="form-group">
									<input 
										type="text" 
										name="email" 
										class="form-control" 
										placeholder="Enter Your Email">
								</div>
								<div class="form-group">
									<input 
										type="password" 
										name="password" 
										class="form-control" 
										placeholder="Create Your Password">
								</div>
								<div class="form-group">
									<input 
                    name="submit" 
                    type="submit" 
                    class="btn btn-primary"
                    value="Login">
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