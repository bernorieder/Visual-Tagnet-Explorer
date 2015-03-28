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
		
	</style>
	
	<h1>Instagram Tagnet</h1>
	
</head>

<?php

ini_set( 'default_charset', 'UTF-8' );
ini_set('memory_limit','128M');
ini_set('max_execution_time', 3000);

ob_end_flush();

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
$getuserinfo = ($_GET["getuserinfo"] == "on") ? true:false;

// check whether the user has granted access
if (isset($code)) {

	// receive OAuth token object
	$data = $instagram->getOAuthToken($code);
	$username = $username = $data->user->username;

	// store user access token
	$instagram->setAccessToken($data);

	$filename = "instagram_" . md5($query) . "_" . $iterations . "_" .date("Y_m_d-H_i_s");
	$taglist = array();
	$ids = array();
	$stats = array();
	$users = array();
	$stats["counter"] = 0;
	$stats["oldest"] = 10000000000000000;
	$stats["newest"] = 0;

	$query = urlencode(preg_replace("/#/","",trim($_GET["tag"])));
	$iterations= trim($_GET["iterations"]);

	echo "getting media, iterations:<br />";

	$result = $instagram->getTagMedia($query, 20);		
	//print_r($result);
	extractTags($result);
	
	echo "1 ";

	for($i = 0; $i < $iterations-1; $i++) {
		echo $i + 2 . " ";
		$result = $instagram->pagination($result);
		extractTags($result);
	}

} else {
	
	// check whether an error occurred
	if (isset($_GET['error'])) {
		echo 'An error occurred: ' . $_GET['error_description'];
	}
}

function extractTags($result) {

	global $taglist,$ids,$stats,$users;

	foreach ($result->data as $media) {

		if(!isset($ids[$media->id])) {
			$ids[$media->id] = true;
		} else {
			echo "already in bucket";
		}

		$taglist[] = $media->tags;
		
		$stats["counter"]++;
		if($media->created_time > $stats["newest"]) {  $stats["newest"] = $media->created_time; }
		if($media->created_time < $stats["oldest"]) {  $stats["oldest"] = $media->created_time; }
		
		
		// populate user lists
		if(!isset($users[$media->user->id])) {
			$users[$media->user->id] = array("id" => $media->user->id, "username" => $media->user->username,"count" => 0);
		}
		$users[$media->user->id]["count"]++;
	}
}



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

file_put_contents($filename.".gdf", $gdf);


// create CSV outputs
$tab_users = "id\tusername\tno_media_in_query\n";

if($getuserinfo) {
	
	$tab_users = "id\tusername\tno_media_in_query\tbio\twebsite\tno_media_all\tfollowed_by\tfollows\n";
	
	$usercounter = 0;
	echo "<br /><br />getting " . count($users) . " users, user:<br />";
	
	foreach($users as $user) {
	
		$usercounter++;
		echo $usercounter . " ";
	
		$result = $instagram->getUser($user["id"]);
	
		$users[$user["id"]]["bio"] = preg_replace("/\s+/"," ",trim($result->data->bio));
		$users[$user["id"]]["website"] = $result->data->website;
		$users[$user["id"]]["media"] = $result->data->counts->media;
		$users[$user["id"]]["followed_by"] = $result->data->counts->followed_by;
		$users[$user["id"]]["follows"] = $result->data->counts->follows;
		
		sleep(0.5);			// to be on the safe side with rate limiting
	}
}

foreach($users as $id => $values) {
	$tab_users .= implode("\t", $values) ."\n";
}

file_put_contents($filename.".tab", $tab_users);



echo '<br /><br />The script has extracted tags from ' . $stats["counter"] . ' media items that were posted between '.date("Y-m-d H:i:s",$stats["oldest"]).' and '.date("Y-m-d H:i:s",$stats["newest"]).'.<br /><br />

your files:<br />
<a href="http://labs.polsys.net/tools/instagram/tagnet/'.$filename.'.gdf">'.$filename.'.gdf</a><br />
<a href="http://labs.polsys.net/tools/instagram/tagnet/'.$filename.'.tab">'.$filename.'.tab</a><br /><br />

NB: Instagram also retrieves media items that once were, but not longer are tagged with the requested term. The date range indicates when media items were posted, but Instagram retrieves media items ordered according to when they were tagged.';

?>

</body>
</html>