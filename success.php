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
	
	<title>Instagram Hashtag Explorer</title>
	
	<link rel="stylesheet" type="text/css" href="main.css" />
</head>

<body>

	<h1>Instagram Hashtag Explorer</h1>

	<p>You are now connected to Instagram.</p>
	
	<p>This script retrieves the latest media tagged with a specified term from Instragram and creates:
	<ul>	
		<li>a tabular file containing a list of media with losts of meta-information;</li>
		<li>a tabular file with information on the users related to those media;</li>
		<li>a co-tag file (GDF format) to analyze e.g. in <a href="http://gephi.org" target="_blank">gephi</a>;</li>
	</ul>
	</p>
	
	<p>Source code and some documentation are available <a href="https://github.com/bernorieder/instagram-tagnet" target="_blank">here</a>.</p>
		
	<p>Specify a tag and the number of media to retrieve:</p>

	<table>
		<form action="tagnet.php" method="get">
			<input type="hidden" name="code" value="<?php echo $_GET["code"]; ?>" />
			<tr>
				<td>Tag:</td>
				<td><input type="text" name="tag" /></td>
				<td></td>
			</tr>
			<tr>
				<td>Iterations:</td>
				<td><input type="text" name="iterations" max="100" /> </td>
				<td>(max. 100, one iteration gets 20 items)</td>
			</tr>
			<tr>
				<td>Get user infos:</td>
				<td><input type="checkbox" name="getuserinfo" max="100" /></td>
				<td>(retrieves additional information for every user, e.g. user bio, can add a lot of time to processing)</td>
			</tr>
			<tr>
				<td>Preview media:</td>
				<td>
					<select name="showimages">
						<option value="off">no preview</option>
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
	
	<p>NB: the public version of this script may run out of memory or into rate limitations.</p>
	
</body>
</html>