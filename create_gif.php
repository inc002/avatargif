<?php
include('resources/animGif.php');
// Use an array containing file paths, resource vars (initialized with imagecreatefromXXX), 
// image URLs or binary image data.
// Optionally: set different durations (in 1/100s units) for each frame
$durations = array(100);
$anim = new GifCreator\AnimGif();
$anim->create($frames, $durations);
//$anim->save("animated.gif");
//$gif = $anim->get();
//header("Content-type: image/gif");

//echo $gif;
$nameGIF = time().rand().".gif";
$anim->save($nameGIF);
$startUrl = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' ;
$urlGIF = $startUrl.$_SERVER['SERVER_NAME'].'/avatargif/'.$nameGIF;
echo '<br /><a href="'.$urlGIF.'">Télécharger le GIF (clic droit : Enregistrer la cible du lien sous...)</a>';
echo '<br /><br /><img src="'.$urlGIF.'">';
exit;

?>
