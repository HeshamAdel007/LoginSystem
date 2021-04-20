<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Africa/Cairo');

if (isset($_POST['submit'])) {

	// Database Conections
	$link	 = mysqli_connect('localhost','heshamadel','123123','login_system') or die ("could not connect to mysql");	

  // Getting Value From Form
	$email 		= $_POST['email'];

  $query = "select * from users where email='$email'";
  $fire  = mysqli_query($link,$query);
  $count = mysqli_num_rows($fire);

	if( $count > 0 ){
		$token = date('dmyhis'); //create unique code
    mysqli_query($link,"update users set token='$token' where email='$email'");

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
      $msg='Verification code sent successfully. Please verify your email.<br><a href="verify.php">Verify Now</a>';
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
	}
	else {

    $msg = "Email account does not match.<br><a href='index.php'>Click Here</a> to Register";

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
              Re-send Code
						</div>
						<div class="card-body">
							<form action="" method="post">
								<div class="form-group">
									<input 
										type="text" 
										name="email" 
										class="form-control" 
										placeholder="Enter Your E-mail">
								</div>
								<div class="form-group">
									<input 
                    name="submit" 
                    type="submit" 
                    class="btn btn-primary"
                    value="Resend Code">
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