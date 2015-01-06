<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
require("class.phpmailer.php");

if($_POST)
{


	$mail = new PHPMailer();
	
    $mike_to_email       = "mike@superiorcarpetandupholsterycare.com,mikesuperiorcarpetcare@yahoo.com,wdhenry727@msn.com"; //Replace with recipient email address
    $mike_subject        = 'Contact Us message sent from Superior Carpet and Upholstery Care public site'; //Subject line for emails
    
    
    //check if its an ajax request, exit if not
  /*  if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    
        //exit script outputting json data
        $output = json_encode(
        array(
            'type'=>'error', 
            'text' => 'Request must come from Ajax'
        ));
        
        die($output);
    }*/ 
    
    //check $_POST vars are set, exit if any missing
    if(!isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userPhone"]) || !isset($_POST["userMessage"]))
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
        die($output);
    }

    //Sanitize input data using PHP filter_var().
    $user_Name        = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
    $user_Email       = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
    $user_Phone       = filter_var($_POST["userPhone"], FILTER_SANITIZE_STRING);
    $user_Message     = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);
    
    //additional php validation
    if(strlen($user_Name)<4) // If length is less than 4 it will throw an HTTP error.
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }
   /* if(!is_numeric($user_Phone)) //check entered data is numbers
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Only numbers allowed in phone field!'));
        die($output);
    }*/
	if(strlen($user_Phone)<7) //check entered data is numbers
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter in a valid phone number.'));
        die($output);
    }
    if(strlen($user_Message)<5) //check emtpy message
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something in the message so we can best serve you.'));
        die($output);
    }
    
    //proceed with PHP email.

    /*
    Incase your host only allows emails from local domain, 
    you should un-comment the first line below, and remove the second header line. 
    Of-course you need to enter your own email address here, which exists in your cp.
    */
    //$headers = 'From: your-name@YOUR-DOMAIN.COM' . "\r\n" .
	
	/*
	Send Email to Mike
	*/
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= "From: ".$user_Email. "\r\n"; 
   // $from = "From: Superior Carpet and Upholstery Care";
	$message = "Message from " . $user_Name . "
                Phone: " . $user_Phone . "
                Email: " . $user_Email . "
                Message: " . $user_Message ;
        // send mail
    $sentMail = @mail($mike_to_email, $mike_subject, $message, $headers);
    
    if(!$sentMail)
    {
       // $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
       // die($output);
    }else{
      //  $output = json_encode(array('type'=>'message', 'text' => 'Thank you '.$user_Name .'! Your message has been received.  We will get back to you with a response as soon as possible.'));
       // die($output);
    }
	
	/*
	Send Email to User
	*/
	$subject        = 'Superior Carpet and Upholstery Care - Contact Us'; //Subject line for emails
    $mail->From = $mike_to_email;
	$mail->FromName = "Superior Carpet & Upholstery Care";
	$mail->AddAddress("$user_Email", $user_Name);
	//$mail->AddReplyTo("info@example.com", "Information");
	$mail->IsHTML(true);                                  // set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = "<!DOCTYPE html>
	<html>
    <head>
	        <link href = 'http://www.superiorcarpetandupholsterycare.com/media/css/bootstrap.min.css' rel = 'stylesheet'>
	        <link href = 'http://www.superiorcarpetandupholsterycare.com/media/css/styles.css' rel = 'stylesheet'>
    </head>
    <body>
		<img class='img-responsive' src='http://www.superiorcarpetandupholsterycare.com/media/images/email-title.jpg' alt='Superior Carpet and Upholstery Care'>
        <h4>Thank you for contacting us with your request!  Here at Superior Carpet &amp; Upholstery Care, we pride ourselves on the level of quality we provide to our customers.
        <p>Someone in our office will get in contact with you within 1 business day.  Of course, you can always contact us any time at </h4><h3>515-971-6220</h3></p>
        <h4>The following is the information we received from you:</h4>
        <table>
            <tr>
                 <td>Name: </td>
                <td>" . $user_Name . "</td>
            </tr>
            <tr>
                <td>Phone: </td>
                <td>" . $user_Phone . "</td>
            </tr>
             <tr>
                <td>Email: </td>
                <td>" . $user_Email . "</td>
            </tr>
            <tr>
                <td>Message: </td>
                <td>" . $user_Message . "</td>
            </tr>
        </table>         
  	      <h5>Superior Carpet &amp; Upholstery Care @ 2014</h5>
	</body>
	</html>";
	
   if(!$mail->Send())
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'text' => 'Thank you '.$user_Name .'! Your message has been received.  We will get back to you with a response as soon as possible.'));
        die($output);
    }
}
?>