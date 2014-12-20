<?php

require "conf.php";
require "php-api/src/Instagram.php";
use MetzWeb\Instagram\Instagram;

$_GET['code'];

if (isset($_GET['error']) || !isset($_GET['code'])) {
	echo 'An error occurred: ' . $_GET['error_description'];
}

function extractTags($result) {

	global $taglist;

	foreach ($result->data as $media) {
		$taglist[] = $media->tags;
	}

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<head>
	<title>Instagram Tagnet</title>
	
	<style type="text/css">
		
		html,body {
			font-family: Arial,Helvetica,sans-serif;
		}
		
	</style>
	
</head>

<body>

	<h1>Instagram Tagnet</h1>

	<p>You are now connected to Instagram. Specify a tag and the number of media to retrieve:</p>

	<form action="tagnet.php" method="get">
		<input type="hidden" name="code" value="<?php echo $_GET["code"]; ?>" />
		Tag: <input type="text" name="tag" /><br />
		Interations: <input type="text" name="iterations" max="100" /> (max. 100, one iteration gets 20 items)<br />
		<input type="submit" />
	</form>
	
	<p>NB: the public version of this script may run out of memory or into rate limitations. Source code is available <a href="https://github.com/bernorieder/instagram-tagnet" target="_blank">here</a>.</p>
	
</body>
</html>