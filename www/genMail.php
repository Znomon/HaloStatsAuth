<?php
require_once 'swift/lib/swift_required.php';

#CHANGE ME
$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
 ->setUsername('username')
 ->setPassword('password');

$mailer = Swift_Mailer::newInstance($transport);

#CHANGE ME
$sql = mysqli_connect('your', 'info', 'goes','here') or die("I couldn't connect to your database, please make sure your info is correct!");

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
$email = isset($_POST['email']) ? $_POST['email'] : '';
$pubKey = isset($_POST['pubKey']) ? $_POST['pubKey'] : ''; 

$action = array();
$action['result'] = null;
 
$text = array();
$ip = $_SERVER['REMOTE_ADDR'];

if(!empty($email)){ 
	 
	//Email Entered
	//echo "Email";
	$req = mysqli_query($sql, "SELECT * FROM `users` WHERE email = '$email'");
	//$row = mysqli_fetch_array($req, MYSQLI_NUM);
	if(mysqli_num_rows($req)>0){
		//$action['result'] = 'error';
		//array_push($text,'Email already in use');
		//echo "Num rows: " . mysqli_num_rows($req);
		//echo 'Email already in use <br/>';
		header('Location: http://66.158.200.153:8081/Auth/errorPage.php?error=Reason: Email already in use.&head=Sorry. Request Failed');
	}
	else{
		reGen:
		$resp = shell_exec("GenKeys.exe");
		#var_dump($resp);
		list($pubKey,$privKey) = explode("##", $resp);
		$privKey = trim($privKey);
		$pubKey = trim($pubKey);
		$uid = shell_exec("GenUID.exe $pubKey");
		$req = mysqli_query($sql, "SELECT * FROM `users` WHERE uid = '&uid'");
		if(mysqli_num_rows($req)>0){
			goto reGen;
		}else {
		//echo "pubkey: " . $pubKey . "[" . strlen($pubKey) . "]" . "<br/>";
		//echo "privkey: " . $privKey . "[" . strlen($privKey) . "]" . "<br/>";
		//echo "email: " . $email . "[" . strlen($email) . "]" . "<br/>";
		//echo "uid: " . $uid . "[" . strlen($uid) . "]" . "<br/>";
		//echo "ip: " . $ip . "[" . strlen($ip) . "]" . "<br/>";
		
		//generate email
		$message = Swift_Message::newInstance('Your HaloStats.Click Keys')
			->setFrom(array('HaloStatsClick@gmail.com' => 'HaloStats.Click'))
			->setTo(array($email))
			->setBody("Open your eldorito folder. Locate 'dewrito_prefs.cfg'. Open in your favorite text editor, and find these lines. \nPlayer.PrivKey \"(large string)\" \nPlayer.PubKey \"(large string)\" \nand replace them with these. \nPlayer.PrivKey \"" . $privKey . "\" \nPlayer.PubKey \"" . $pubKey . "\"", 'text/plain');

		$result = $mailer->send($message);
		//send email
		mysqli_query($sql, "INSERT INTO users (id, email, pubKey, UID, IP, privateKey) VALUES (NULL,'$email','$pubKey','$uid','$ip','$privKey')") or die (mysqli_error($sql));
		header('Location: http://66.158.200.153:8081/Auth/errorPage.php?head=Email Sent!');
		}
	}
}else if(!empty($pubKey)){ 
	//PubKey Entered
	
	if(strlen($pubKey) < 500)
	{
		header('Location: http://66.158.200.153:8081/Auth/errorPage.php?head=Invalid pubKey');
	}
	else
	{
		$uid = shell_exec("GenUID.exe $pubKey");
		$req = mysqli_query($sql, "SELECT * FROM `users` WHERE (uid = '&uid') or (pubKey = '".$pubKey."')");
		
		mysqli_query($sql, "INSERT INTO users (id, email, pubKey, UID, IP, privateKey) VALUES (NULL,NULL,'$pubKey','$uid','$ip',NULL)") or die (header('Location: http://66.158.200.153:8081/Auth/errorPage.php?head=ERROR: UID IN USE&error=Please sign-up for a unique key by email'));
		header('Location: http://66.158.200.153:8081/Auth/errorPage.php?head=UID Verified&error=Change Nothing, and Continue Playing. Thanks for Verifying         TESTING PURPOSES: Your UID: '. $uid .'');
	}
}

?>