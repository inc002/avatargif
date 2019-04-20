<?php
include('resources/simple_html_dom.php');


// INIT 

//twitter : ProfileAvatar-image 
//telegram : tgme_page_photo_image
//instagram : _6q-tv

$tabSN = array ();
$tabSN[0]['name'] = 'twitter';
$tabSN[0]['class_img'] = 'ProfileAvatar-image';
$tabSN[0]['url'] = 'https://twitter.com/';
$tabSN[1]['name'] = 'telegram';
$tabSN[1]['class_img'] = 'tgme_page_photo_image';
$tabSN[1]['url'] = 'https://t.me/';
$tabSN[2]['name'] = 'instagram';
$tabSN[2]['class_img'] = '_6q-tv';
$tabSN[2]['url'] = 'https://www.instagram.com/';
$folderGIF = 'GIF/';
$error = false;
if (!isset ($_GET['n']) OR empty($_GET['n']) OR !is_numeric($_GET['n']) OR $_GET['n']>2){
//choose ID of Social Network 
// 0 : twitter
// 1 : telegram
	$idSN = 0;
}else{
	$idSN = $_GET['n'];
}

function getWords($sentenceBrut){
	$sentencePlus = str_replace(' ','+',$sentenceBrut);
	$tabWordsBrut = explode('+',$sentencePlus);
	return $tabWordsBrut;
}

function pre($var){
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}

function getTop50TwitterAccount(){
	//wikitable sortable jquery-tablesorter
	$url = 'https://en.wikipedia.org/wiki/List_of_most-followed_Twitter_accounts';
	$html = file_get_html($url);
	if ($html){
		foreach($html->find('"table.wikitable sortable" tr') as $element){
			foreach($element->find('td') as $cell) {
				// push the cell's text to the array
				if (substr($cell->plaintext,0,1)==='@'){
					$tabTwittos[] = $cell->plaintext;
				} 
			}
		}
		return $tabTwittos;
	}
	return false;
}

function randomGIF($tabName){
	$nbRandom = rand(2,8);
	$nbTabName = count($tabName);	
	$string = '';
	for ($i=0;$i<$nbRandom;$i++){
		$valRandom = rand($i,$nbTabName);
		$string .= $tabName[$valRandom].' ';
	}
	return $string;
}

//pre(randomGIF(getTop50TwitterAccount()));

function printOut($urlGIF,$idSN,$tabSN,$s,$listAccount,$error){
	if (empty($listAccount)){
		$account = explode(' ',$s);
		for ($i=0;$i<count($account);$i++){
			$listAccount .= '<a href="'.$tabSN[$idSN]['url'].$account[$i].'">@'.$account[$i].'</a> ';
		}
		//
		//$listAccount = '@'.$listAccount;
	}
	$display = '	<br /><a href="'.$urlGIF.'">Download GIF (right click : Save target as...)</a>
					<br /><br /><img src="'.$urlGIF.'">
					<br /><br />Images from '.$tabSN[$idSN]['name'].
					'<br /><br />Avatar of : '.$listAccount.'&nbsp;
					<br /><br /><a href="#" onclick="window.history.back();">Back</a>';
	
	if (empty($s) or !isset($s)){
		$display = '
		<h1>Create a GIF with avatars of social network people</h1>
		<h3>Just write some nicknames from social network, separated by spaces</h3>
		<form><input type="text" name="s" id="s"><br />
		<br/>
		<input type="radio" name="n" id="n0" value="0" checked>
		<label for="s1">Twitter</label>	
		<input type="radio" name="n" id="n1" value="1">
		<label for="s1">Telegram</label>
		<input type="radio" name="n" id="n2" value="2">
		<label for="s1">Instagram</label>
		<br />
		<br />
		<input type="submit"></form>
		<form>
		<a href="?r=0">Random Top 50 twitter account</a>
		</form>
		'; 
	}
	if ($error){
		$display = $error;
	}
	$out = 
		'<html>
			<header>
			</header>
			<body>
				<div align="center">
				'.$display.'
				</div>
			</body>
		</html>';
	
		return $out;
} 
if (isset($_GET['r']) AND is_numeric($_GET['r']))	{
	$_GET['s'] = randomGIF(getTop50TwitterAccount());
	$_GET['n'] = $_GET['r'];
	
}

$nameGIF = $folderGIF.md5($_GET['s'].$_GET['n']).".gif";
$startUrl = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' ;
$urlGIF = $startUrl.$_SERVER['SERVER_NAME'].'/avatargif/'.$nameGIF;

if (!file_exists($nameGIF)){
	//Get url of avatar pictures
	$resTabWords = getWords($_GET['s']);
	$nbWords = count($resTabWords);
	$listAccount = '';
	for ($i=0;$i<$nbWords;$i++){
		// Create DOM from URL or file
		$url = $tabSN[$idSN]['url'].$resTabWords[$i].'';
		if (!empty($resTabWords[$i])){
			$html = file_get_html($url);
			if ($html){
				$resTabWords[$i] = str_replace('@','',$resTabWords[$i]);
				$listAccount .= '<a href="'.$url.'">@'.$resTabWords[$i].'</a> ';
				if ($idSN == 2){
					//meta property="og:image"
					foreach($html->find('meta[property=og:image]') as $element){
						$frames[] = $element->content;
					}
				}else{
					foreach($html->find('img.'.$tabSN[$idSN]['class_img']) as $element){
						$frames[] = file_get_contents($element->src);
					}
				}
			}
		}
	}
	//Create GIF
	$nbFrames = count($frames);
	if ($nbFrames > 1){
		include ('create_gif.php');
		$error = false;
	}elseif(isset($_GET['s'])){
		$error = 'Error : No GIF available<br /><br /><a href="#" onclick="window.history.back();">Back</a>';
	}
	
}
echo printOut($urlGIF,$idSN,$tabSN,$_GET['s'],$listAccount,$error);







?>
