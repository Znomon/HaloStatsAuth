<?php
function GET($name=NULL, $value=false, $option="default")
{
    $option=false; // Old version depricated part
    $content=(!empty($_GET[$name]) ? trim($_GET[$name]) : (!empty($value) && !is_array($value) ? trim($value) : false));
    if(is_numeric($content))
        return preg_replace("@([^0-9])@Ui", "", $content);
    else if(is_bool($content))
        return ($content?true:false);
    else if(is_float($content))
        return preg_replace("@([^0-9\,\.\+\-])@Ui", "", $content);
    else if(is_string($content))
    {
        if(filter_var ($content, FILTER_VALIDATE_URL))
            return $content;
        else if(filter_var ($content, FILTER_VALIDATE_EMAIL))
            return $content;
        else if(filter_var ($content, FILTER_VALIDATE_IP))
            return $content;
        else if(filter_var ($content, FILTER_VALIDATE_FLOAT))
            return $content;
        else
            return preg_replace("@([^a-zA-Z0-9\+\-\_\*\@\$\!\;\.\?\#\:\=\%\/\ ]+)@Ui", "", $content);
    }
    else false;
}

if(GET('error')){$error = GET('error');}
else{$error = '';}
if(GET('head')){$head = GET('head');}
else{$head = '';}

?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>HaloStats.Click</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	
	<body>

		<!-- Header -->
			<header id="header">
				<center>
					<h1><?php echo $head; ?></h1><br />
					<h2><?php echo $error; ?></h2>
				</center>
			</header>


		<!-- Footer -->
			<footer id="footer">
				<ul class="icons">
					<li><a href="mailto:halostatsclick@gmail.com?subject=WebSupport%20Request" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
				</ul>
				<ul class="copyright">
					<li>&copy; HaloStats.Click</li>
				</ul>
			</footer>

		<!-- Scripts -->
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	<style>
	.content {
		width: 85%;
		margin: 0 auto;
	}
	#wrapper {
		
		overflow: hidden; 
	}
	#first {
		float:left; 
	}
	#second {
		float: right; 
	}
	
	</style>
	
	</body>
</html>