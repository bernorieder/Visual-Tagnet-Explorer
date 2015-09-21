<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Instagram Hashtag Explorer</title>
	
	<link rel="stylesheet" type="text/css" href="main.css" />
	
	<style type="text/css">
		
		table, td, th {
		    border-color: #000;
		    border-style: solid;
		}
		
		table {
			width: 100%;
		    border-width: 0 0 1px 1px;
		    border-spacing: 0;
		    border-collapse: collapse;
		}
		
		td, th {
		    margin: 0;
		    padding: 2px;
		    border-width: 1px 1px 0 0;
		}

	</style>
</head>

<body>

<h1>Instagram Hashtag Explorer</h1>

<?php

ini_set('default_charset', 'UTF-8');
ini_set('memory_limit', '128M');
ini_set('max_execution_time', 3000);

require "conf.php";
require "php-api/src/Instagram.php";
use MetzWeb\Instagram\Instagram;

// initialize class
$instagram = new Instagram(array(
  'apiKey'      => $apiKey,
  'apiSecret'   => $apiSecret,
  'apiCallback' => $apiCallback
));

// receive OAuth code parameter
$code = $_GET['code'];


// check GET variables
$getuserinfo = ($_GET["getuserinfo"] == "on") ? true:false;
$showimages = ($_GET["showimages"] == "off") ? false:$_GET["showimages"];
$query = urlencode(preg_replace("/#/","",trim($_GET["tag"])));
$iterations= trim($_GET["iterations"]);
$mode = $_GET["mode"];

// check whether the user has granted access
if(isset($code)) {

	// receive OAuth token object
	$data = $instagram->getOAuthToken($code);
	$username = $username = $data->user->username;

	// store user access token
	$instagram->setAccessToken($data);

	// create some basic variables
	$taglist = array();
	$ids = array();
	$stats = array();
	$users = array();
	$media = array();
	$stats["counter"] = 0;
	$stats["oldest"] = 10000000000000000;
	$stats["newest"] = 0;


	if($mode == "last") {
		
		$filename = "data/instagram_" . md5($query) . "_" . $iterations . "_" .date("Y_m_d-H_i_s");
		
		echo "getting media, iterations:<br />";
		
		if($_GET["tag"] == "") { echo "missing tag"; exit; }
		if($_GET["iterations"] > 100) { echo "iteration parameter problem"; exit; }
		
		// API calls for media, get one, then loop
		$result = $instagram->getTagMedia($query, 20);		
		extractTags($result);
		
		echo "1 "; flush(); ob_flush();
	
		for($i = 0; $i < $iterations-1; $i++) {
			echo $i + 2 . " ";
			$result = $instagram->pagination($result,20);
			extractTags($result);
		}
	}

	
	if($mode == "location") {
		
		$filename = "data/instagram_" . $_GET["lat"] . "_" . $_GET["lng"] . "_" . $_GET["distance"] . "_" .date("Y_m_d-H_i_s");
		
		echo "getting media, retrieved:<br />";
		
		if($_GET["lat"] == "" || $_GET["lng"] == "" || $_GET["distance"] == "") { echo "location parameter problem"; exit; }
		if($_GET["date_start"] == "" || $_GET["date_end"] == "") { echo "timespan parameter problem"; exit; }
		
		$lat = $_GET["lat"];
		$lng = $_GET["lng"];
		$distance = $_GET["distrance"];
		$date_start = strtotime($_GET["date_start"] . " 23:59:59");
		$date_end = strtotime($_GET["date_end"] . " 00:00:00");
		$date_end_fake = $date_end - 7 * 60 * 60 * 24;
				
		while($date_start > $date_end) {
			
			$result = $instagram->searchMedia($lat,$lng,$distance,$date_end_fake,$date_start);
			extractTags($result);
		
			foreach ($result->data as $medium) {
				if($medium->created_time < $date_start ) {  $date_start = $medium->created_time; }
			}
		
			echo count($media) . "(" . date("Y-m-d H:i:s",$date_start) . ") "; flush(); ob_flush();
		}
	}


} else {
	
	// check whether an error occurred
	if (isset($_GET['error'])) {
		echo 'An error occurred: ' . $_GET['error_description'];
	}
}

function extractTags($result) {

	if(isset($result->meta->error_message)) {
		print_r($result->meta);
		echo '<p>Error may be related to <a href="http://blog.instagram.com/post/21454597658/instagrams-new-guidelines-against-self-harm">http://blog.instagram.com/post/21454597658/instagrams-new-guidelines-against-self-harm</a></p>';
		exit;
	}

	global $taglist,$ids,$stats,$users,$media,$showimages;

	foreach ($result->data as $medium) {
		
		if($medium->type != "image") { continue; }

		if(!isset($ids[$medium->id])) {
			$ids[$medium->id] = true;
		} else {
			continue;
		}

		$taglist[] = $medium->tags;
		
		$stats["counter"]++;
		if($medium->created_time > $stats["newest"]) {  $stats["newest"] = $medium->created_time; }
		if($medium->created_time < $stats["oldest"]) {  $stats["oldest"] = $medium->created_time; }
		
		
		// populate user lists
		if(!isset($users[$medium->user->id])) {
			$users[$medium->user->id] = array("id" => $medium->user->id, "user_name" => $medium->user->username,"no_media_in_query" => 0);
		}
		$users[$medium->user->id]["no_media_in_query"]++;
		
		
		// populate media lists
		if(!isset($media[$medium->id])) {

			$tmp_location = (isset($medium->location->latitude)) ? $medium->location->latitude.", ".$medium->location->longitude:"";
			$tmp_thumbnail = ($showimages == false) ? $medium->images->thumbnail->url:$medium->images->{$showimages}->url;
			
			$media[$medium->id] = array("id" => $medium->id,
										"created_time" => date("Y-m-d H:i:s", $medium->created_time),
										"location" => $tmp_location,
										"no_comments" => $medium->comments->count,
										"no_likes" => $medium->likes->count,
										"filter" => $medium->filter,
										"link" => $medium->link,
										"caption" => preg_replace("/\s+/"," ",trim($medium->caption->text)),
										"thumbnail" => $tmp_thumbnail,
										"tags" => implode(", ", $medium->tags),
										"user_name" => $medium->user->username,
										"user_id" => $medium->user->id
										);
		}
	}
}


// create tag network
$tags = array();
$edges = array();

foreach($taglist as $tmptags) {

	// iterate over half of ajacency matrix
	for($i = 0; $i < count($tmptags); $i++) {

		$tmptags[$i] = strtolower($tmptags[$i]);
		$tmptags[$i] = preg_replace("/,/", " ", $tmptags[$i]);

		if(!isset($tags[$tmptags[$i]])) {
			$tags[$tmptags[$i]] = 1;
		} else {
			$tags[$tmptags[$i]]++;
		}

		for($j = $i; $j < count($tmptags); $j++) {

			$tmptags[$j] = strtolower($tmptags[$j]);
			$tmptags[$j] = preg_replace("/,/", " ", $tmptags[$j]);

			$tmpedge = array($tmptags[$i],$tmptags[$j]);
			asort($tmpedge);
			$tmpedge = implode("_|||_", $tmpedge);

			if(!isset($edges[$tmpedge])) {
				$edges[$tmpedge] = 1;
			} else {
				$edges[$tmpedge]++;
			}
		}
	}
}

arsort($tags);



// create GDF output
$gdf = "nodedef>name VARCHAR,label VARCHAR,count INT\n";
foreach($tags as $key => $value) {
	$gdf .= md5($key) . "," . $key . "," . $value . "\n";
}

$gdf .= "edgedef>node1 VARCHAR,node2 VARCHAR,weight INT\n";

foreach($edges as $key => $value) {
	$tmpedge = explode("_|||_", $key);
	$gdf .= md5($tmpedge[0]) . "," . md5($tmpedge[1]) . "," . $value . "\n";
}

file_put_contents($filename."_tagnet.gdf", $gdf);



// create user TAB output
$tab_users = "id\tusername\tno_media_in_query\n";

if($getuserinfo) {
	
	$tab_users = "id\tusername\tno_media_in_query\tbio\twebsite\tno_media_all\tfollowed_by\tfollows\n";
	
	$usercounter = 0;
	echo "<br /><br />getting " . count($users) . " users, user:<br />";
	
	foreach($users as $user) {
	
		$usercounter++;
		echo $usercounter . " "; flush(); ob_flush();
	
		$result = $instagram->getUser($user["id"]);
	
		$users[$user["id"]]["user_bio"] = preg_replace("/\s+/"," ",trim($result->data->bio));
		$users[$user["id"]]["user_website"] = $result->data->website;
		$users[$user["id"]]["user_no_media"] = $result->data->counts->media;
		$users[$user["id"]]["user_followed_by"] = $result->data->counts->followed_by;
		$users[$user["id"]]["user_follows"] = $result->data->counts->follows;
		
		sleep(0.5);			// to be on the safe side with rate limiting
	}
}

foreach($users as $id => $values) {
	$tab_users .= implode("\t", $values) ."\n";
}

file_put_contents($filename."_users.tab", $tab_users);



// create media TAB output
if($getuserinfo) {
	foreach($media as $medium) {
		$media[$medium["id"]] = array_merge($media[$medium["id"]],$users[$medium["user_id"]]);
	}
}

$tab_media = implode("\t", array_keys($media[array_shift(array_keys($media))])) . "\n";

foreach($media as $medium) {
	$tab_media .= implode("\t", $medium) ."\n";
}

file_put_contents($filename."_media.tab", $tab_media);




// HTML output
echo '<p>The script has extracted tags from ' . $stats["counter"] . ' media items that were posted between '.date("Y-m-d H:i:s",$stats["oldest"]).' and '.date("Y-m-d H:i:s",$stats["newest"]).'.</p>';

$files = array($filename."_tagnet.gdf",$filename."_media.tab",$filename."_users.tab");

zipit($filename,$files);

echo 'NB: Instagram also retrieves media items that once were, but not longer are tagged with the requested term. The date range indicates when media items were posted, but Instagram retrieves media items ordered according to when they were tagged.<br /><br />';



// HTML data table
if($showimages) {
	
	//print_r($media);
	
	echo '<table>';
	
	echo '<tr>';
	foreach(array_keys($media[array_shift(array_keys($media))]) as $title) {
		echo '<th>'.$title.'</th>';
	}
	echo '</tr>';
	
	foreach($media as $medium) {
		
		echo '<tr>';
		foreach($medium as $element) {
			
			if(preg_match("/\.jpg/", $element)) {
				echo '<td><div><img src="'.$element.'" /></div></td>';	
			} else if(preg_match("/https:/", $element) || preg_match("/http:/", $element)) {
				echo '<td><a href="'.$element.'">'.$element.'</a></td>';
			} else {
				echo '<td>'.$element.'</td>';
			}
			
		}
		echo '</tr>';
	}
	echo '</table>';
}


function zipit($filename,$files) {

	echo '<p>Compressing files...</p>'; flush(); ob_flush();

	$zip = new ZipArchive();
	$filename = $filename . ".zip";

	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    	exit("cannot open <$filename>\n");
	}

	foreach ($files as $file) {
		$cleanfile = preg_replace("/\.\/data\//", "", $file);		// cleaning up the filename to counter uncompress problems (with "." maybe?)
		$zip->addFile($file,$cleanfile);
		echo $cleanfile . "<br />";
	}

	echo '<p>Your files have been generated. ' . $zip->numFiles . ' files were zipped. ';
	echo 'Download the <a href="'.$filename.'">zip archive</a>.</p>';

	$zip->close();

	foreach ($files as $file) {
		unlink($file);
	}
}


?>

</body>
</html>