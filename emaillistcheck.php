<?php
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
date_default_timezone_set("America/New_York");

$current = date("Y-m-d");
$q = mysqli_query($conn,"Select ListID,SendDate From EmailList");
while($r = mysqli_fetch_array($q)) {
    if($r['SendDate']==$current) {
        $fullq = mysqli_query($conn,"Select * from EmailList where ListID='$r[ListID]'");
        $fullr = mysqli_fetch_array($fullq);
        
        //# Sending email
		if(mail($fullr['EmailTo'], $fullr['EmailSubject'], $fullr['EmailMessage'], $fullr['EmailHeaders'])){
		    echo 'Your mail has been sent successfully.';
		} else {
			echo 'Unable to send email. Please try again.';
		}
    }
}
?>