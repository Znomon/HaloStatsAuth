<?php
// require_once 'swift/lib/swift_required.php';

// $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
  // ->setUsername('HaloStatsClick')
  // ->setPassword('halo stats are amazing');

// $mailer = Swift_Mailer::newInstance($transport);

// $message = Swift_Message::newInstance('Test Subject')
  // ->setFrom(array('HaloStatsClick@gmail.com' => 'HaloStats.Click'))
  // ->setTo(array('kasey.xbox@gmail.com'))
  // ->setBody('This is a test mail.');

// $result = $mailer->send($message);
// 




$sql = mysqli_connect('localhost', 'root', 'Kingsage25','halostats') or die("I couldn't connect to your database, please make sure your info is correct!");

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  

$email = isset($_POST['email']) ? $_POST['email'] : '';
$pubKey = isset($_POST['pubKey']) ? $_POST['pubKey'] : ''; 
#if($_GET['email']){$email = mysqli_real_escape_string($sql, $_POST['email']);}

#if($_GET['pubKey']){$pubKey = mysqli_real_escape_string($sql, $_POST['pubKey']);}





$action = array();
$action['result'] = null;
 
$text = array();

if(!empty($email)){ 
	 
	//Email Entered
	//echo "Email";
	$req = mysqli_query($sql, "SELECT * FROM `users` WHERE email = 'kasey.xbox@gmail.com'");
	//$row = mysqli_fetch_array($req, MYSQLI_NUM);
	if(mysqli_num_rows($req)>0){
		//$action['result'] = 'error';
		//array_push($text,'Email already in use');
		echo 'Email already in use <br/>';
	}
	else{
		$resp = shell_exec("GenKeys.exe");
		list($pubKey,$privKey) = explode("##", $resp);
		$uid = shell_exec("GenUID.exe $pubKey");
		$ip = $_SERVER['REMOTE_ADDR'];
		//check if UID is unique
			//else generate new public/private rinse repeat
		//send email
		mysqli_query($sql, "INSERT INTO `users` VALUES(NULL,'$email','$pubKey','$uid','$ip','$privKey')") or die ("query failed");
	}
}else if(!empty($pubKey)){ 
	//PubKey Entered
	echo "PubKey";
	#$_SERVER['REMOTE_ADDR'];
	//generate UID
	//check if UID unique
	//if(yes)
		//add UID to database
		#mysqli_query($sql, "INSERT INTO `users` VALUES(NULL,NULL,'$pubKey','$uid','$ip')") or die ("query failed");
	//else
		//inform user, and redirect to signup page
}


if($action['result'] != 'error'){
    //no errors, continue signup
       #$password = md5($password);
}
     
$action['text'] = $text;


$uid = 54321; //= shell_exec("GenUID.exe $pubkey");

$ip = $_SERVER['REMOTE_ADDR'];
//add to the database
$add = mysqli_query($sql, "INSERT INTO `users` VALUES(NULL,'$email','$pubKey','$uid','$ip')") or die ("query failed");
         
if($add){
 
    //the user was added to the database    
             
}else{
         
    $action['result'] = 'error';
    array_push($text,'User could not be added to the database. Reason: ' . mysql_error());
}


?>