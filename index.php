<?php

ini_set( 'default_charset', 'UTF-8' );

require "conf.php";
require "php-api/src/Instagram.php";
use MetzWeb\Instagram\Instagram;

// initialize class
$instagram = new Instagram(array(
  'apiKey'      => $apiKey,
  'apiSecret'   => $apiSecret,
  'apiCallback' => $apiCallback
));

// create login URL
$loginUrl = $instagram->getLoginUrl();

?>

<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>Instagram Hashtag Explorer</title>
	
	<link rel="stylesheet" type="text/css" href="main.css" />
</head>

<body>

<table>
	
	<tr>
		<td >
			
		<h1>Instagram Hashtag Explorer</h1>
	
		<p>This small tool retrieves either the latest media tagged with a specified term or the media around a particular location and creates:
		<ul>	
			<li>a tabular file containing a list of media with lots of meta-information;</li>
			<li>a tabular file with information on the users related to those media;</li>
			<li>a co-tag file (GDF format) to analyze e.g. in <a href="http://gephi.org" target="_blank">gephi</a>;</li>
		</ul>
		</p>
		
		<p>For more information on how to use this tool, check out this <a href="https://www.youtube.com/watch?v=o07aUKdRv0g" target="_blank">video</a>.</p>
		
		<p>Before using the tool, you may want to have a look at the FAQ section <a href="faq.php" target="_blank">here</a>.</p>
		
		<p>Launch the tool by <a href="<?php echo $loginUrl; ?>">connecting to Instagram</a>.<p>
		
		</td>
	</tr>
</table>

</body>
</html>