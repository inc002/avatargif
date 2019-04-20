<?php
include('resources/animGif.php');
// Use an array containing file paths, resource vars (initialized with imagecreatefromXXX), 
// image URLs or binary image data.
// Optionally: set different durations (in 1/100s units) for each frame
$durations = array(100);
$anim = new GifCreator\AnimGif();
$anim->create($frames, $durations);
$gif = $anim->get();
header("Content-type: image/gif");
echo $gif;
exit;

?>
