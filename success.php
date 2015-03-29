<?php

ini_set( 'default_charset', 'UTF-8' );

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

<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>Instagram Tagnet</title>
	
	<style type="text/css">
		
		html,body {
			font-family: Arial,Helvetica,sans-serif;
			font-size: 12px;
		}
		
		td {
			padding: 5px;
		}
		
	</style>
	
</head>

<body>

	<h1>Instagram Tagnet</h1>

	<p>You are now connected to Instagram. Specify a tag and the number of media to retrieve:</p>

	<table>
		<form action="tagnet.php" method="get">
			<input type="hidden" name="code" value="<?php echo $_GET["code"]; ?>" />
			<tr>
				<td>Tag:</td>
				<td><input type="text" name="tag" /></td>
				<td></td>
			</tr>
			<tr>
				<td>Interations:</td>
				<td><input type="text" name="iterations" max="100" /> </td>
				<td>(max. 100, one iteration gets 20 items)</td>
			</tr>
			<tr>
				<td>Get user infos:</td>
				<td><input type="checkbox" name="getuserinfo" max="100" /></td>
				<td>(can add a lot of time to processing)</td>
			</tr>
			<tr>
				<td>Preview media:</td>
				<td>
					<select name="showimages">
						<option value="off">no media</option>
						<option value="thumbnail">thumbnail</option>
						<option value="low_resolution">low resolution</option>
						<option value="standard_resolution">standard resolution</option>
					</select>
				</td>
				<td>(can slow down the browser for big queries)</td>
			</tr>
			<tr>
				<td colspan="3"><input type="submit" /></td>
			</tr>
		</form>
	<table>
	
	<p>NB: the public version of this script may run out of memory or into rate limitations. Source code is available <a href="https://github.com/bernorieder/instagram-tagnet" target="_blank">here</a>.</p>
	
</body>
</html>