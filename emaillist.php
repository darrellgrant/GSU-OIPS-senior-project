<!DOCTYPE html>
<html>
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
$imgdir = "/files/images/";
?>
<?php 
	if (strlen($_POST['FileSel'])>0 || strlen($_POST['fileselcopy'])>0) {		
		//# gets POST and gets contents as $page and email array
		$emailarr = array();
		$emailarr = $_POST['emaillist'];
		$filesel = $_SERVER['DOCUMENT_ROOT'] . "/files/" . $_POST['FileSel'];
		$emailsubj;
		$emailrep;
		$emailcc;
		//# if the fileselcopy is sent, change $emailarr and $filesel
		if(isset($_POST['fileselcopy'])) {
		    $emailarr = $_POST['emaillistcopy'];
		    $filesel = $_SERVER['DOCUMENT_ROOT'] . "/files/" . $_POST['fileselcopy'];
		}
		//# grab correct email detail info
		if(strlen($_POST['emailsubj1'])>0) {
		    $emailsubj = $_POST['emailsubj1'];
		    $emailrep = $_POST['emailrep1'];
		    $emailcc = $_POST['emailcc1'];
		}
		else if (strlen($_POST['emailsubj2'])>0) {
		    $emailsubj = $_POST['emailsubj2'];
		    $emailrep = $_POST['emailrep2'];
		    $emailcc = $_POST['emailcc2'];
		}
		//# get send date and file -> $page
		$senddate = $_POST['senddate'];
		$page = file_get_contents($filesel);
		
		//# fix image directory
		$page2 = str_replace("src=\"images/image1.png\"","src=\"".$imgdir."image1.png"."\"",$page);
		
		//# put emailllist into an array split on ,
		$emailsplit = explode(",",$emailarr);
		
		//# display an Email Preview with inserted variables
		$pageshow = insertVars($conn,$page2,$emailsplit[0]);
		echo $pageshow;
		
		//# if the Send button is submitted, put the entries into EmailList for scheduled delivery
		if(isset($_POST['schedule'])) {
		    foreach($emailsplit as $e) {
		        //$e = varFilter($e);
		        $page = insertVars($conn,$page2,$e);
		        setCron($conn,$e,$page,$senddate,1,$emailsubj,$emailrep,$emailcc);
		    }
		}
		//# if the Send Now button is pressed, send an email now (urgency=0)
		if(isset($_POST['sendnow'])) {
		    foreach($emailsplit as $e) {
		        $page = insertVars($conn,$page2,$e);
		        setCron($conn,$e,$page,0,0,$emailsubj,$emailrep,$emailcc);
		    }
		}
	}
	
	//# filter the array of values with mysql escape strings, html filter, and trim excess whitespace
    function varFilter($s){
        $val = $s;
    	$val = mysqli_real_escape_string($conn,$val);
    	$val = filter_var($val,FILTER_SANITIZE_STRING);
    	return trim($val);
    }
	
	function insertVars($conn,$page,$email) {
	    $em = $email;
		# get all information required from Student,Passports,Program
		if (!mysqli_query($conn,"Select * from Student where Email='$em'")) {
			echo("Error retrieving Student data: " . mysqli_error($con));
		}
		else {
			$studentquery = mysqli_query($conn,"Select * from Student where Email='$em'");
			$studentrow = mysqli_fetch_array($studentquery);
			$eagleid = $studentrow['EagleID'];
			
			$applicquery = mysqli_query($conn,"Select * from Application where EagleID='$eagleid'");
    		$applicrow = mysqli_fetch_array($applicquery);
    		$progid = $applicrow['ProgramID'];
    		
			if (!mysqli_query($conn,"Select * from Passports where EagleID='$eagleid'")) {
				echo("Error retrieving Passport data: " . mysqli_error($con));
			}
			else {
    			$passquery = mysqli_query($conn,"Select * from Passports where EagleID='$eagleid'");
    			$passportrow = mysqli_fetch_array($passquery);
    			if (!mysqli_query($conn,"Select * from Program where ProgramID='$progid'")) {
    				echo("Error retrieving Program data: " . mysqli_error($con));
    			}
    			else {
    				$programquery = mysqli_query($conn,"Select * from Program where ProgramID=$progid");
    				$programrow = mysqli_fetch_array($programquery);
    				//# set values to look for and appropriate values
    				$valmap = array("*|Eagle ID|*",$studentrow['EagleID'],
    				"*|First Name|*",$studentrow['FirstName'],
    				"*|Middle Name|*",$studentrow['MiddleName'],
    				"*|Last Name|*",$studentrow['LastName'],
    				"*|Preferred Name|*",$studentrow['PrefName'],
    				"*|Email|*",$studentrow['Email'],
    				"*|Phone Number|*",$studentrow['PhoneNum'],
    				"*|First Name as shown in passport|*",$passportrow['PassFName'],
    				"*|Middle Name as shown in passport|*",$passportrow['PassMName'],
    				"*|Last Name as shown in passport|*",$passportrow['PassLName'],
    				"*|Passport Nationality|*",$passportrow['PassportNatl'],
    				"*|Passport Number|*",$passportrow['PassportNum'],
    				"*|Passport Expiration Date|*",$passportrow['PassportExpDate'],
    				"*|Program Name|*",$programrow['ProgramName'],
    				"*|Semester|*",$programrow['Semester'],
    				"*|1st Payment Amount|*",$programrow['Payment1Amt'],
    				"*|1st Payment Date|*",$programrow['Payment1DateDue'],
    				"*|2nd Payment Amount|*",$programrow['Payment2Amt'],
    				"*|2nd Payment Date|*",$programrow['Payment2DateDue'],
    				"*|3rd Payment Amount|*",$programrow['Payment3Amt'],
    				"*|3rd Payment Date|*",$programrow['Payment3DateDue'],
    				"*|4th Payment Amount|*",$programrow['Payment4Amt'],
    				"*|4th Payment Date|*",$programrow['Payment4DateDue'],
    				"*|Insurance Amount|*",$programrow['InsuranceAmt'],
    				"*|Insurance Payment Date|*",$programrow['InsuranceDateDue']);
    				//# call count once to reduce load
    				$ct = count($valmap);
    				//# iterate through valmap to make date values easier to read
    				for($i=0;$i<$ct;$i++) {
    					if ((stripos($valmap[$i],"date")) !== false) {
    						$dttemp = date_create_from_format('Y-m-j',$valmap[$i+1]);
    						$valmap[$i+1] = date_format($dttemp,'F jS\, Y');
    					}
    				}
				//# iterate through valmap to look for values to replace in $page
				for($i=0;$i<$ct;$i+=2) {
					 if ((stripos($page,$valmap[$i])) !== false) {
						$pagetemp = str_ireplace($valmap[$i],$valmap[$i+1],$page);
						$page = $pagetemp;
					}
				}
				return $page;
				}
			}
		}
	}
	
	function setCron($conn,$email,$message,$senddate,$urgency,$subj,$rep,$cc) {
		$subject = $subj;
		$from = 'no-reply-oips@georgiasouthern.edu';
		//# To send HTML mail, the Content-type header must be set
		$headers = "MIME-Version: 1.0 \r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1 \r\n";
		//# Create email headers
		$headers .= "From: " . $from . "\r\n" .
			"Reply-to:" . $rep . "\r\n";
			//'CC:' . $cc . "\r\n";
		
		//# an urgency flag=1 means it is scheduled
		if($urgency == 1) {
    		$eid;
    		//# Get EagleID from Email
    		if (!mysqli_query($conn,"Select EagleID from Student where Email='$email'")) {
    				echo("Error retrieving EagleID from sent email: $email");
    			}
    		else {
    			$q = mysqli_query($conn,"Select EagleID from Student where Email='$email'");
    			$r = mysqli_fetch_array($q);
    			$eid = $r['EagleID'];
    		}
    		//# insert entry into EmailList table
    		if (!mysqli_query($conn,"INSERT into EmailList (EagleID,EmailTo,EmailSubject,EmailMessage,EmailHeaders,SendDate) VALUES ('$eid','$email','$subject','$message','$headers','$senddate')")) {
    				echo("Error putting in automatic email entry: " . mysqli_error($conn));
    			}
    		else {
    			mysqli_query($conn,"INSERT into EmailList (EagleID,EmailTo,EmailSubject,EmailMessage,EmailHeaders,SendDate) VALUES ('$eid','$email','$subject','$message','$headers',$senddate')");
    		}
		}
		
		//# urgency == 0 means the user wanted to send the emails immediately
		if ($urgency == 0) {
		    mail($email,$subject,$message,$headers);
		}
	}
?>
</html>
