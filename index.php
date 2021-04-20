<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Africa/Cairo');

if (isset($_POST['submit'])) {
	// Getting Value From Form
	$name  		  = $_POST['name'];
	$email 		  = $_POST['email'];
	$mobile 	  = $_POST['mobile'];
	$password   = $_POST['password'];
	$token		  = date('dmyhis'); //create unique code
	$formErrors = array();

	// Database Conections
	// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$link	 = mysqli_connect('localhost','heshamadel','123123','login_system') or die ("could not connect to mysql");
	$query = mysqli_query($link,"select * From users Where email='$email'");
	$count = mysqli_num_rows($query);

	if (isset($name)) {

		$filterdUser = filter_var($name, FILTER_SANITIZE_STRING);

		if (strlen($filterdUser) < 4) {

			$formErrors[] = 'Username Must Be Larger Than 4 Characters';

		}

	}

	if (isset($email)) {

		$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

		if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

			$formErrors[] = 'This Email Is Not Valid';

		}

	}

	if (isset($password)) {
		$filterdpass = filter_var($password, FILTER_VALIDATE_INT, ["options" => ["min_range" => 8]]);
		if (empty($password)) {

			$formErrors[] = 'Sorry Password Cant Be Empty';

		}
		if (filter_var($filterdpass, FILTER_VALIDATE_INT) != true) {

			$formErrors[] = 'Password Must Be Larger Than 8 Characters';

		}

	}

	if( $count > 0 ){
		$msg = "Email Already Exist. Try another Email. or <a href='login.php'>Click Here</a> to login";
	}
	elseif (empty($formErrors)) {
		//New Email
		$query ="insert into users(
							name,
							email,
							mobile,
							password,
							token,
							status
						) 
						values (
							'$name',
							'$email',
							'$mobile',
							'$password',
							'$token',
							'0'
						)";

		$fire = mysqli_query($link, $query);

		if ($fire) {
			
			// Send E-Mail
			require './PHPMailer/src/Exception.php';
			require './PHPMailer/src/PHPMailer.php';
			require './PHPMailer/src/SMTP.php';

			$mail = new PHPMailer(true);

			try {
				//Settings
				$mail->isSMTP();
				$mail->Host 				= 'smtp.mailtrap.io';
				$mail->Port  				= 2525;
				$mail->SMTPAuth			= true;
				$mail->SMTPSecure   = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->Username			= '25d82a583fab56'; //Sender
				$mail->Password			= 'b623b698763467';

				$mail->setFrom('heshamadel528@gmail.com', 'Notification');
				$mail->addAddress($email); //Receiver
				$mail->addReplyTo('noreply@heshamadel.com', 'noReply');

				//Content
				$mail->isHTML(true);
				$mail->Subject = 'Login System With E-mail Verification';
				$mail->Body    = '<h1 style="background:navy;color:white;padding:10px;text-align:center;">Email Verifcation</h1><p>Your Email Verification Code is <b>'.$token.'</b></p>';
				$mail->send();
    		$msg='You are registered successfully. Please verify your email.<br><a href="verify.php">Verify Now</a>';
			} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}

		} else {

			$msg = "Something Wrong";
		}

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
					if (!empty($formErrors)) {

						foreach ($formErrors as $error) {?>
	
							<div class="alert alert-danger" role="alert">
								<strong>Alert!</strong> <?php echo $error; ?>
							</div>
						<?php
		
						}
		
					}
					if (isset($succesMsg)) {?>

						<div class="alert alert-success" role="alert">
								<strong>Alert!</strong> <?php echo $succesMsgr; ?>
							</div>
					?>
					<?php
					}
					//setting Error Message Here
					if(isset($msg)): ?>
						<div class="alert alert-warning" role="alert">
							<strong>Alert!</strong> <?php echo $msg; ?>
						</div>
					<?php endif; ?>
					<div class="card text-center">
						<div class="card-header text-white bg-primary">
							Registration Form
						</div>
						<div class="card-body">
							<form action="" method="post">
								<div class="form-group">
									<input 
										type="text" 
										name="name" 
										class="form-control" 
										placeholder="Enter Your Name">
								</div>
								<div class="form-group">
									<input 
										type="text" 
										name="email" 
										class="form-control" 
										placeholder="Enter Your Email">
								</div>
								<div class="form-group">
									<input 
										type="text" 
										name="mobile" 
										class="form-control" 
										placeholder="Enter Your Mobile Number">
								</div>
								<div class="form-group">
									<input 
										type="password" 
										name="password" 
										class="form-control" 
										placeholder="Create Your Password">
								</div>
								<div class="form-group">
									<input type="reset" class="btn btn-info">
									<input name="submit" type="submit" class="btn btn-primary">
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