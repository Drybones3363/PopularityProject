<html>
<head> <title> Twitter Search </title> </head>
<body>



<?php
error_reporting(E_ERROR | E_PARSE);
function tonumber($s) {
	//echo $s;
	$s = str_replace(",","",$s);
	$ret = intval($s);
	if (strpos($s,'K')) {
		return 1000*$ret;
	}else if (strpos($s,'M')) {
		return 1000000*$ret;
	}
	return $ret;
}

$user = isset($_GET['user']) ? $_GET['user'] : '';

if ($user == '') {
	echo '<form method="GET" action="twitterstuff.php">
<h2> Search Username Here: </h2> <br>
<input name="user" type = "text" value=""></input><br>
<input type="submit" value="Submit"></input>
</form>';

}
else {
	$twitter = @file_get_contents('https://twitter.com/'.$user);
	$stuff = array();
	$stuff["TwitterFollowers"] = 0;
	$stuff["YoutubeSubscribers"] = 0;
	if (isset($twitter)) {
		$n = strpos($twitter,'href="/'.$user.'/followers"');
		$fpos = strpos($twitter,'data-is-compact="',$n)+strlen('data-is-compact="');
		$ss = strpos(substr($twitter,$fpos,100),">");
		$strr = strpos(substr($twitter,$fpos+$ss,25),"<");
		$stuff["TwitterFollowers"] = tonumber(substr($twitter,$fpos+$ss+1,$strr-1));
	}
	$youtube = @file_get_contents('https://youtube.com/user/'.$user);
	if (isset($youtube)) {
		$apos = strpos($youtube,'class="yt-subscription-button-subscriber-count-branded-horizontal subscribed yt-uix-tooltip"')+strlen('class="yt-subscription-button-subscriber-count-branded-horizontal subscribed yt-uix-tooltip"');
		$bpos = strpos($youtube,'title="',$apos);
		$cpos = strpos($youtube,'"',$bpos+strlen('title="'));
		$stuff["YoutubeSubscribers"] = tonumber(substr($youtube,$bpos+strlen('title="'),$cpos-$bpos-strlen('title="')));
	}
	$rating = floor(sqrt(3*(2*$stuff["YoutubeSubscribers"]+$stuff["TwitterFollowers"])));
	if ($rating < 0) {$rating = 0;}
	elseif ($rating > 1000) {$rating = 1000;}
	echo '<h2>'.$user.'</h2>
		<h3> Popularity Rating: </h3>
		<h4 id="i"></h4>
		<script>
			function rand() {
				document.getElementById("i").innerHTML = Math.floor((Math.random() * 1000) + 1);
			}
			function rating() {
				document.getElementById("i").innerHTML = '.$rating.';
			}
			for (var i=0;i<100;i++) {
				setTimeout(rand,i*33);
			}
			setTimeout(rating,100*33);
		</script>
		<br>
		<form method="GET" action="twitterstuff.php">
<h2> Search Username Here: </h2> <br>
<input name="user" type = "text" value=""></input><br>
<input type="submit" value="Submit"></input>
</form>
	';


}









?>



</body>
</html>
