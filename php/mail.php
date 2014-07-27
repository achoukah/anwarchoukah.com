<?php

// Initialize
$to = '[b]choukah@me.com[/b]';
$mail_sent = false;
$id = substr(number_format(time() * rand(), 0, '', ''), 0, 5);
$email = $name = $subject = $message = $error = $offer = $offer_text = '';

// Process the request
if(filter_has_var(INPUT_POST, 'message')) {
  // Antispam check
  $cake = filter_input(INPUT_POST, 'cake');
  
  if($cake != 'OK')
    $error = "An error occured.";
  
  // Get E-Mail
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  
  if(($email === NULL) || ($email === ''))
    $error = "Missing email.";
  
  else if($email === false) {
    $error = "Invalid email.";
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  }
  
  // Get name
  $name = filter_input(INPUT_POST, 'name');
  
  if(($name === NULL) || ($name === false) || empty($name) || ($name === ''))
    $error = "Missing name.";
  
  // Get message
  $message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);
  
  if(($message === NULL) || ($message === false) || empty($message) || ($message === ''))
    $error = "Missing message.";
  
  // Try to send the mail
  if(!$error) {
    if(mail($to, utf8_decode('Contact #'.$id), utf8_decode($message), utf8_decode("From: $name <$email>\nReply-to: $email\n")))
      $mail_sent = true;
    else
      $error = "Could not send message.";
  }
}

else
  $error = "Invalid request.";

// Generate the response
$status_code = '1';
$status_message = "Message sent.";

if(!$mail_sent) {
  $status_code = '0';
  
  if($error)
    $status_message = $error;
  else
    $status_message = "Message not sent.";
}

$response = '<frenchtouch xmlns="frenchtouch:php:mail">';
  $response .= '<status>'.htmlspecialchars($status_code).'</status>';
  $response .= '<message>'.htmlspecialchars($status_message).'</message>';
$response .= '</frenchtouch>';

// Output the response
header('Content-Type: text/xml');
echo $response;

?>