<?php
$dropbox_token = $argv[1];
$plugin_version = "1.0.0";
$plugin_slug = 'lwpcommerce';
$data = array();

// Read Changelog
try {
	$changelog = fopen( dirname( dirname(__FILE__) ) . "/CHANGELOG.md", "r");
} catch (Exception $e) {
	echo 'File does not exist';
}

$row = 1;
$tagchild = false;

while (($content = fgets($changelog)) !== false) {

	// Get Version
	if (strpos($content, "# [v") !== false) {
		if (preg_match("/\[(.*?)\]/", $content, $matches)) {
			$data["version"] = substr($matches[1], 1);
			$plugin_version = substr($matches[1], 1);
			$data["changelog"]["version"] = $matches[1];

			// Get Date and News Link
			if (preg_match_all("#\((.*?)\)#", $content, $matches)) {
				$data['changelog']["link"] =  $matches[1][0];
				$data['changelog']["date"] =  $matches[1][1];
			}
		}
	}

	// Get Content
	if (($start = strpos($content, "**")) !== false) {

		$end = strpos($content, " :**");
		$title = substr($content, $start + 2, $end - $start - 2);
		$title = str_replace(" ", "_", strtolower($title));
		//echo $str . PHP_EOL;

		$data['changelog'][$title] = array();
		//$tagchild = true;
		$tagparent = $title;
	}

	// Push Changelog 
	if (($start = strpos($content, "- ")) !== false) {
		//if ($tagchild){
		array_push($data['changelog'][$tagparent], trim(substr($content, 2)));
		//}
	}
}
fclose($changelog);


$data['slug'] = $plugin_slug;
$data['agent'] = "Github";

// Upload to DropBox
$file_zip = dirname( dirname(__FILE__) ) . '/build/' . $plugin_slug . '.zip';
$fp = fopen($file_zip, 'rb');
$size = filesize($file_zip);

$cheaders = array(
	'Authorization: Bearer ' . $dropbox_token,
	'Content-Type: application/octet-stream',
	'Dropbox-API-Arg: {"path":"/' . $plugin_slug . '/' . $plugin_slug . '-' . $plugin_version  . '.zip", "mode":"add", "autorename" : true}'
);

$ch = curl_init('https://content.dropboxapi.com/2/files/upload');
curl_setopt($ch, CURLOPT_HTTPHEADER, $cheaders);
curl_setopt($ch, CURLOPT_PUT, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_INFILE, $fp);
curl_setopt($ch, CURLOPT_INFILESIZE, $size);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

// echo $response;
curl_close($ch);
fclose($fp);

$result = json_decode($response);
$dropbox_path = $result->path_lower;
// var_dump($dropbox_path);

$parameters = array(
	'path' => $dropbox_path,
	'settings' => array("audience" => "public")
);

$headers = array(
	'Authorization: Bearer ' . $dropbox_token,
	'Content-Type: application/json'
);

$curlOptions = array(
	CURLOPT_HTTPHEADER => $headers,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => json_encode($parameters),
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_VERBOSE => true
);

$ch = curl_init('https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings');
curl_setopt_array($ch, $curlOptions);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response);
$raw_url = $result->url;

// Handle Shared Link was Exist
if (!isset($result->url)) {
	$parameters = array(
		'path' => $dropbox_path,
	);

	$headers = array(
		'Authorization: Bearer ' . $dropbox_token,
		'Content-Type: application/json'
	);

	$curlOptions = array(
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => json_encode($parameters),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_VERBOSE => true
	);

	$ch = curl_init('https://api.dropboxapi.com/2/sharing/list_shared_links');
	curl_setopt_array($ch, $curlOptions);
	$response = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($response);
	$raw_url = $result->links[0]->url;
}

$download_url = str_replace("dl=0", "dl=1", $raw_url);

$data['download_url'] = $download_url;

$fp = fopen('release.json', 'w');
fwrite($fp, json_encode($data));
fclose($fp);

die();
