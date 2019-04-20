<?php
error_reporting(E_ALL);

include('simple_html_dom.php');

function getWords($sentence){
	$sentencePlus = str_replace(' ','+',$sentence);
	$tabWords = explode('+',$sentencePlus);
	return $tabWords;
}

function pre($var){
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}

$resTabWords = getWords($_GET['s']);
$nbWords = count($resTabWords);

//twitter : ProfileAvatar-image 
//telegram : tgme_page_photo_image
//facebook : img dans div profilePicThumb 


$tabSN = array ();
$tabSN[0]['name'] = 'twitter';
$tabSN[0]['class_img'] = 'ProfileAvatar-image';
$tabSN[0]['url'] = 'https://twitter.com/';
$tabSN[1]['name'] = 'telegram';
$tabSN[1]['class_img'] = 'tgme_page_photo_image';
$tabSN[1]['url'] = 'https://t.me/';

//choose ID of Social Network 
// 0 : twitter
// 1 : telegram
$idSN = 0;

for ($i=0;$i<$nbWords;$i++){
	// Create DOM from URL or file
	$url = $tabSN[$idSN]['url'].$resTabWords[$i];
	$html = file_get_html($url);
	foreach($html->find('img.'.$tabSN[$idSN]['class_img']) as $element){
		$frames[] = file_get_contents($element->src);
	}
}

include ('create_gif.php');





?>
