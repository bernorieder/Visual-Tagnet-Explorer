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
$loginUrl = $instagram->getLoginUrl(array("public_content"));

?>

<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>Visual Tagnet Explorer</title>
	
	<link rel="stylesheet" type="text/css" href="main.css" />
</head>

<body>

<table>
	
	<tr>
		<td >
			
		<h1>Visual Tagnet Explorer</h1>
	
		<hr />
	
		<p><strong style="color:red;">Since Instagram has recently changed its platform regulations, this tool has stopped working on June 1 2016. More information <a href="http://thepoliticsofsystems.net/2016/05/closing-apis-and-the-public-scrutiny-of-very-large-online-platforms/">here</a>.</strong></p>
	
		<hr />
	
		<p>This is an app for researchers and brand analysts to create Instagram co-tag networks around keywords or places. The tool retrieves either the latest media tagged with a specified term or the media around a particular location and creates:
		<ul>	
			<li>a tabular file containing a list of media with lots of meta-information;</li>
			<li>a tabular file with information on the users related to those media;</li>
			<li>a co-tag file (GDF format) to analyze e.g. in <a href="http://gephi.org" target="_blank">gephi</a>;</li>
		</ul>
		</p>
		
		<p>For more information on how to use this tool, check out this <a href="https://www.youtube.com/watch?v=o07aUKdRv0g" target="_blank">video</a>.</p>
		
		<p>Before using the tool, you may want to have a look at the <a href="faq.php" target="_blank">FAQ</a> or the <a href="privacy.php" target="_blank">privacy policy</a>.</p>
		
		<p>Launch the tool by <a href="<?php echo $loginUrl; ?>">connecting to Instagram</a>.<p>
		
		</td>
	</tr>
</table>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-68146659-2', 'auto');
  ga('send', 'pageview');
</script>

</body>
</html>