<?php
include('resources/simple_html_dom.php');


// INIT 

//twitter : ProfileAvatar-image 
//telegram : tgme_page_photo_image

$tabSN = array ();
$tabSN[0]['name'] = 'twitter';
$tabSN[0]['class_img'] = 'ProfileAvatar-image';
$tabSN[0]['url'] = 'https://twitter.com/';
$tabSN[1]['name'] = 'telegram';
$tabSN[1]['class_img'] = 'tgme_page_photo_image';
$tabSN[1]['url'] = 'https://t.me/';


if (!isset ($_GET['n']) OR empty($_GET['n']) OR !is_numeric($_GET['n']) OR $_GET['n']>1){
//choose ID of Social Network 
// 0 : twitter
// 1 : telegram
	$idSN = 0;
}else{
	$idSN = $_GET['n'];
}



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


//Get url of avatar pictures
$resTabWords = getWords($_GET['s']);
$nbWords = count($resTabWords);

for ($i=0;$i<$nbWords;$i++){
	// Create DOM from URL or file
	$url = $tabSN[$idSN]['url'].$resTabWords[$i];
	$html = file_get_html($url);
	foreach($html->find('img.'.$tabSN[$idSN]['class_img']) as $element){
		$frames[] = file_get_contents($element->src);
	}
}
echo '<html>
<header>
</header>
<body>
<div align="center">';

//Create GIF
include ('create_gif.php');

echo '
</div>
</body>
</html>';






?>
