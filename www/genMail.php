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

$email = mysqli_real_escape_string($sql, $_POST['email']);
$pubKey = mysqli_real_escape_string($sql, $_POST['pubKey']);

$action = array();
$action['result'] = null;
 
$text = array();

if(empty($email)){ 
	$action['result'] = 'error';
	array_push($text,'You forgot your email'); 	
}
if(empty($pubKey)){ 
	$action['result'] = 'error';
	array_push($text,'pubKey empty');
}


if($action['result'] != 'error'){
    //no errors, continue signup
       #$password = md5($password);
}
     
$action['text'] = $text;


$uid = 54321; //= shell_exec("GenUID.exe $pubkey");


//add to the database
$add = mysqli_query($sql, "INSERT INTO `users` VALUES(NULL,'$email','$pubKey','$uid')") or die ("query failed");

if (mysqli_connect_errno())
  {
  echo "Failed to query to MySQL: " . mysqli_connect_error();
  }
         
if($add){
 
    //the user was added to the database    
             
}else{
         
    $action['result'] = 'error';
    array_push($text,'User could not be added to the database. Reason: ' . mysql_error());
}


?>