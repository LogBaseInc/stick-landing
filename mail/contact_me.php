<?php
// Check for empty fields

error_log("Contact form submitted");

if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }
	
$name = $_POST['name'];
$email_address = $_POST['email'];
$message = $_POST['message'];
	
// Create the email and send the message
$to = 'abishek@logbase.io'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Stick Contact Form:  $name";
$email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\n\nMessage:\n$message";
$headers = "From: noreply@logbase.io\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$headers .= "Reply-To: $email_address";	
//mail($to,$email_subject,$email_body,$headers);

// Sendgrid start

 $url = 'https://api.sendgrid.com/';
 $user = getenv("SENDGRID_USER");
 $pass = getenv("SENDGRID_KEY"); 

 error_log("Sendgrid user: " + $user);

 $params = array(
      'api_user' => $user,
      'api_key' => $pass,
      'to' => 'abishek@logbase.io',
      'subject' => $email_subject,
      'html' => $email_body,
      'text' => $email_body,
      'from' => 'noreply@logbase.io',
   );

 $request = $url.'api/mail.send.json';

 // Generate curl request
 $session = curl_init($request);

 // Tell curl to use HTTP POST
 curl_setopt ($session, CURLOPT_POST, true);

 // Tell curl that this is the body of the POST
 curl_setopt ($session, CURLOPT_POSTFIELDS, $params);

 // Tell curl not to return headers, but do return the response
 curl_setopt($session, CURLOPT_HEADER, false);
 curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

 // obtain response
 $response = curl_exec($session);
 curl_close($session);

 // print everything out
 print_r($response);
 error_log("Contact form submission ends");

// Sendgrid End

return true;			
?>