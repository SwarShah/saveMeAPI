<?php
require_once('class.phpmailer.php');
include_once("class.smtp.php");
//used for sending sms
function sendMsg($params){
	include("../credentials.php");
	$postData = "";
	foreach($params as $k => $v){
		$postData.= $k . '='.$v.'&'; 
	}
	rtrim($postData, '&');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_POST, count($postData));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$output=curl_exec($ch);
	curl_close($ch);
	return $output;
}
//used to send email
function sendEmail($email, $location, $name){
	include("../credentials.php");
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug  = 2;
	$mail->SMTPAuth   = true;
	$mail->Host       = $mailHost;
	$mail->Port       = $mailPort;
	$mail->Username   = $mailUsername;
	$mail->Password   = $mailPassword;
	$mail->SetFrom('no-reply@swar.webs.pm', 'saveME');
	$mail->Subject    = $name." is in emergency";
	$mail->MsgHTML('<html><body><a href="http://maps.google.com/?q='.$location.'"><img src="http://maps.google.com/maps/api/staticmap?zoom=16&markers='.$location.'&size=500x300"/></a></body></html>');
	$address = $email;
	$mail->AddAddress($address);
	if(!$mail->Send()) {
	  echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	  echo "Mail sent!";
	}
}
if(isset($_POST['phone'], $_POST['location'], $_POST['email'], $_POST['name']) && !empty($_POST['phone']) && !empty($_POST['location']) && !empty($_POST['email']) && !empty($_POST['name'])){
	//echo "Got everything";
	//params for sms
	$params = array(
		"From" => "+12267786035",
		"To" => $_POST['phone'],
		"Body" => "Hi, We found ".$_POST['name']." is in emergency. Last known location: http://maps.google.com/?q=".$_POST['location']
	); 
	//calling functions  
	echo sendMsg($params);
	sendEmail($_POST['email'], $_POST['location'], $_POST['name']);
}
else{
	echo "Not enough arguments";
}
?>