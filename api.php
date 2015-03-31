<?php
function sendMsg($params){
	$url="URL for API";
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
function sendEmail($email, $location){

}
if(isset($_POST['phone'], $_POST['location'], $_POST['email'])){
	//echo "Got everything";
	$params = array(
		"From" => "+12267786035",
		"To" => $_POST['phone'],
		"Body" => $_POST['location']
	); 
	//echo sendMsg($params);
	sendEmail($_POST['email'], $_POST['location']);
}
else{
	echo "Not enough arguments";
}
?>