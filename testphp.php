<html>
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";

//echo "<br>";
$a = "<a href='test'>Test</a> ; delete table";
//echo $a . "<br>";
//echo arrFilter($a);

$b = array("");
$c = array("roy"=>"m","toy"=>"n","");
//print_r(array_diff($c,$b));

$array = array(
    'fruit1' => 'apple',
    'fruit2' => 'orange',
    'fruit3' => 'grape',
    'fruit4' => 'apple',
    'fruit5' => 'apple');
// this cycle echoes all associative array
// key where value equals "apple"
while ($fruit_name = current($array)) {
if ($fruit_name !== 'apple') {
        //echo key($array)." ".$fruit_name.'<br />';
    }
    next($array);
}

function arrFix(&$s){
    $p = array();
	$q = array();
	$count = count($s);
	//# Strip escape strings and html tags
	for($i=1;$i<$count;$i+=2) {
	    $p[$i-1] = $s[$i-1];
	    $p[$i] = arrFilter($s[$i]);
	}
	//# Filter out empty entries and remove key ambiguities 
	for($i=0;$i<$count;$i+=2){
		if($s[$i+1]!=""){
			array_push($q,$p[$i],$p[$i+1]);
		}
	}
	var_dump($q);
}

//# filter the array of values with mysql escape strings, html filter, and trim excess whitespace
function arrFilter(&$s){
    $val = $s;
    //$val = mysqli_real_escape_string($conn,$val);
	$val = filter_var($val,FILTER_SANITIZE_STRING);
	return trim($val);
}

$appid = "";
    $maxquery = "Select Max(AppID) from Application";
    $maxres = mysqli_query($conn,$maxquery);
    $max = mysqli_fetch_array($maxres);
    echo $max['Max(AppID)'];

echo $appid . "ben";

?>
</html>