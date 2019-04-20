# avatargif
Create a GIF with the name of all your favorites person of twitter or telegram

Usage : 
Call index.php?s=name1 name2 name3 nameN

Example : http://127.0.0.1/avatargif/?s=hello%20wonderfull%20girl

Output : 
GIF directly in browser with 1 sec of animation delay 

Q&A :

 - How choose social network ? 
   * By use param n in URL
   0 : twitter
   1 : telegram
   * By change value of var $idSN in line 23 of index.php page 
 - How change animation delay ?
   * By update the value (in milliseconds) of $durations in create_gif.php (it's array, it's normal)  
   

This shitware use http://sourceforge.net/projects/simplehtmldom/ and https://github.com/lunakid/AnimGif 
