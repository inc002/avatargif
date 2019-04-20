<?php
include('resources/animGif.php');
$durations = array(100);
$anim = new GifCreator\AnimGif();
$anim->create($frames, $durations);
$anim->save($nameGIF);
?>
