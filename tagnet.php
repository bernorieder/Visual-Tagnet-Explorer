<?php

ini_set('memory_limit','128M');

require "conf.php";
require "php-api/src/Instagram.php";
use MetzWeb\Instagram\Instagram;

// initialize class
$instagram = new Instagram(array(
  'apiKey'      => '6815a8983545492e9cda7a318feadda0',
  'apiSecret'   => '915678fec1fa4e228272a83de9ff63eb',
  'apiCallback' => 'http://labs.polsys.net/tools/instagram/tagnet/success.php' // must point to success.php
));

// receive OAuth code parameter
$code = $_GET['code'];

// check whether the user has granted access
if (isset($code)) {

	// receive OAuth token object
	$data = $instagram->getOAuthToken($code);
	$username = $username = $data->user->username;

	// store user access token
	$instagram->setAccessToken($data);

	$taglist = array();
	$ids = array();
	$stats = array();
	$stats["counter"] = 0;
	$stats["oldest"] = 10000000000000000;
	$stats["newest"] = 0;

	$query = preg_replace("/#/","",trim($_GET["tag"]));
	$iterations= trim($_GET["iterations"]);

	$result = $instagram->getTagMedia($query, 20);
	extractTags($result);

	for($i = 0; $i < $iterations-1; $i++) {
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

	global $taglist,$ids,$stats;

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
	
	<h1>Instagram Tagnet</h1>
	
</head>

<?php

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


// create output
$gdf = "nodedef>name VARCHAR,label VARCHAR,count INT\n";
foreach($tags as $key => $value) {
	$gdf .= md5($key) . "," . $key . "," . $value . "\n";
}

$gdf .= "edgedef>node1 VARCHAR,node2 VARCHAR,weight INT\n";

foreach($edges as $key => $value) {
	$tmpedge = explode("_|||_", $key);
	$gdf .= md5($tmpedge[0]) . "," . md5($tmpedge[1]) . "," . $value . "\n";
}

$filename = "instagram_" . $query . "_" . $iterations . "_" .date("Y_m_d-H_i_s") . ".gdf";

file_put_contents($filename, $gdf);



echo 'The script has extracted tags from ' . $stats["counter"] . ' media items that were posted between '.date("Y-m-d H:i:s",$stats["oldest"]).' and '.date("Y-m-d H:i:s",$stats["newest"]).'.<br /><br />


your file: <a href="http://labs.polsys.net/tools/instagram/tagnet/'.$filename.'">'.$filename.'</a><br /><br />

NB: Instagram also retrieves media items that one were, but not longer are tagged with the requested term. The date range indicates when media items were posted, but Instagram retrieves media items ordered according to when they were tagged.';

?>

</body>
</html>