<?php

ini_set( 'default_charset', 'UTF-8' );

require "conf.php";
require "php-api/src/Instagram.php";
use MetzWeb\Instagram\Instagram;


if (isset($_GET['error']) || !isset($_GET['code'])) {
	echo 'An error occurred: ' . $_GET['error_description'];
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

<table>
	
	<form action="tagnet.php" method="get">
		<input type="hidden" name="code" value="<?php echo $_GET["code"]; ?>" />
	
		<tr>
			<td colspan="3">
	
			<h1>Instagram Hashtag Explorer</h1>
		
			<p><b>You are now connected to Instagram.</b></p>
			
			<p>This script retrieves either the latest media tagged with a specified term or the media around a particular location and creates:
			<ul>	
				<li>a tabular file containing a list of media with lots of meta-information;</li>
				<li>a tabular file with information on the users related to those media;</li>
				<li>a co-tag file (GDF format) to analyze e.g. in <a href="http://gephi.org" target="_blank">gephi</a>;</li>
			</ul>
			</p>
			
			<p>When using the location mode and a large date range, retrieval may take very long, run out of memory, or run into rate limits.<br />
				It is strongly recommended to test a very small date range first (e.g. a single day), to get an understanding how many media are posted at that location.</p>
				
			<p>For more information on how to use this tool, check out this <a href="https://www.youtube.com/watch?v=o07aUKdRv0g" target="_blank">video</a>.</p>
				
			<p>Before using the tool, you may want to have a look at the <b>FAQ</b> section <a href="faq.php" target="_blank">here</a>.</p>
	
			</td>
		</tr>

		<tr>
			<td colspan="3"><hr /></td>
		</tr>		
		<tr>
			<td colspan="3">1) choose a method:</td>
		</tr>
		
		<tr>
			<td><input type="radio" name="mode" value="last" checked="checked" /></td>
			<td>Tag:</td>
			<td><input type="text" name="tag" /></td>
		</tr>
		<tr>
			<td></td>
			<td>Iterations:</td>
			<td><input type="text" name="iterations" max="100" /> (max. 100, one iteration gets 20 items)</td>
		</tr>
		
		<tr>
			<td colspan="3"><hr /></td>
		</tr>
		
		<tr>
			<td><input type="radio" name="mode" value="location" /></td>
			<td>location:</td>
			<td>lat: <input type="text" name="lat" /> lng: <input type="text" name="lng" /> distance: <input type="text" name="distance" /> (in meters, max. 5000) </td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>date range: <input type="text" name="date_end" /> - <input type="text" name="date_start" /> (YYYY-MM-DD, more recent date second)</td>
		</tr>
		
		<tr>
			<td colspan="3"><hr /></td>
		</tr>
		
		<tr>
			<td colspan="3">2) options:</td>
		</tr>
		
		<tr>
			<td></td>
			<td>Get user infos:</td>
			<td><input type="checkbox" name="getuserinfo" max="100" /> (retrieves additional information for every user, e.g. user bio, can add a lot of time to processing)</td>
		</tr>
		<tr>
			<td></td>
			<td>Preview media:</td>
			<td>
				<select name="showimages">
					<option value="off">no preview</option>
					<option value="thumbnail">thumbnail</option>
					<option value="low_resolution">low resolution</option>
					<option value="standard_resolution">standard resolution</option>
				</select>
				(images can slow down the browser for big queries)
			</td>
		</tr>
		
		<tr>
			<td colspan="3"><hr /></td>
		</tr>
		
		<tr>
			<td colspan="3"><input type="submit" /></td>
		</tr>
	</form>
<table>
	
	
</body>
</html>