<?php
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
$dbConnection = new PDO('mysql:dbname=u736324170_oips;host=localhost;charset=utf8', 'u736324170_oips', 'oKv3f7CYKjaz');
$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<html>
<head>
<style>
.close,
.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
  position:fixed;
  top:5px;
  right:5px;
  background-color: red;
  border: 1px solid red;
  color: white;
  padding: 10px 24px;
  font-size: 12px;
}          
</style>  
</head>
<body>
    <button class="close" onclick="parent.closeEdit()">Cancel</button>
<?php
//# on Update submit, try to update student
if($_POST['update']) {
    //# get POST data
    $eagleid = $_POST['eid'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $fn = $_POST['fn'];
    $mn = $_POST['mn'];
    $ln = $_POST['ln'];
    $pn = $_POST['pn'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $gender = $_POST['gen'];
    $race = $_POST['race'];
    $ethnicity = $_POST['eth'];

    $emergname = $_POST['emergname'];
    $emergrela = $_POST['emergrela'];
    $emergphone = $_POST['emergphone'];
    $emergemail = $_POST['emergemail'];
    
    $passverif = $_POST['passverif'];
    $passfn = $_POST['passfn'];
    $passmn = $_POST['passmn'];
    $passln = $_POST['passln'];
    $passnatl = $_POST['passnatl'];
    $passnum = $_POST['passnum'];
    $passexpdate = $_POST['passexp'];
    
    $class = $_POST['educclass'];
    $major = $_POST['major'];
    $gpa = $_POST['gpa'];
    $cdh = $_POST['cdh'];
    $acstand = $_POST['acstand'];
    
    $appid = $_POST['appid'];
    $judverif = $_POST['judverif'];
    $judverifnote = $_POST['judverifnote'];
    $letter = $_POST['letsent'];
    $enrverif = $_POST['enrollverif'];
    $gpaverif = $_POST['gpaverif'];
    $classes = array();
    array_push($classes,$_POST['classes1'],$_POST['classes2'],$_POST['classes3'],$_POST['classes4'],$_POST['classes5'],$_POST['classes6'],$_POST['classes7'],$_POST['classes8'],$_POST['classes9'],$_POST['classes10']);
    $deppaid = $_POST['deppaid'];
    $depositdt = $_POST['depositdt'];
    $p1paid = $_POST['p1paid'];
    $p1dt = $_POST['p1dt'];
    $p2paid = $_POST['p2paid'];
    $p2dt = $_POST['p2dt'];
    $p3paid = $_POST['p3paid'];
    $p3dt = $_POST['p3dt'];
    $p4paid = $_POST['p4paid'];
    $p4dt = $_POST['p4dt'];
    $inspaid = $_POST['insurpaid'];
    $insdt = $_POST['insurdt'];
    $bal = $_POST['bal'];
    //default Update query for Application (must be different due to ProgramID foreign key dependency) 
    $query2 = "UPDATE Application SET EagleID='$eagleid', JudicialVerif='$judverif', JudicialVerifNote='$judverifnote', LetterSent='$letter', EnrollmentVerif='$enrverif', GPAVerif='$gpaverif', Course1='$classes[0]', Course2='$classes[1]', Course3='$classes[2]', Course4='$classes[3]', Course5='$classes[4]', Course6='$classes[5]', Course7='$classes[6]', Course8='$classes[7]', Course9='$classes[8]', Course10='$classes[9]', DepositPaid='$deppaid', DepositDatePaid='$depositdt', Payment1Paid='$p1paid', Payment1DatePaid='$p1dt', Payment2Paid='$p2paid', Payment2DatePaid='$p2dt', Payment3Paid='$p3paid', Payment3DatePaid='$p3dt', Payment4Paid='$p4paid', Payment4DatePaid='$p4dt', InsurancePaid='$inspaid', InsuranceDatePaid='$insdt', BalanceDue='$bal' WHERE AppID='$appid';";
    //if this is somehow a new Application entry, insert
    if(strlen($appid)==0) {
    $maxquery = "Select Max(AppID) from Application";
        if(mysqli_query($conn,$maxquery)) {
            $maxquery = "Select Max(AppID) from Application";
            $maxres = mysqli_query($conn,$maxquery);
            $max = mysqli_fetch_array($maxres);
            $appid = (int)$max['Max(AppID)'] + 1;
            $query2 = "INSERT INTO Application (AppID,EagleID,ProgramID,JudicialVerif,JudicialVerifNote,LetterSent,EnrollmentVerif,GPAVerif,Course1,Course2,Course3,Course4,Course5,Course6,Course7,Course8,Course9,Course10, BalanceDue, DepositPaid, DepositDatePaid, Payment1Paid, Payment1DatePaid, Payment2Paid, Payment2DatePaid, Payment3Paid, Payment3DatePaid, Payment4Paid, Payment4DatePaid, InsurancePaid, InsuranceDatePaid) VALUES ('$appid','$eagleid','0','$judverif','$judverifnote','$letter','$enrverif','$gpaverif','$classes[0]','$classes[1]','$classes[2]','$classes[3]','$classes[4]','$classes[5]','$classes[6]','$classes[7]','$classes[8]','$classes[9]', '$bal', $deppaid', '$depositdt', '$p1paid','$p1dt', '$p2paid','$p2dt', '$p3paid', '$p3dt', '$p4paid', '$p4dt', '$inspaid', '$insdt');";
        }
        else {
            echo mysqli_error($conn);
        }
    }
    $query = "INSERT INTO Student (EagleID,FirstName,MiddleName,LastName,PrefName,Email,PhoneNum,DOB,Age,Gender,Race,Ethnicity) VALUES ('$eagleid','$fn','$mm','$ln','$pn','$email','$phone','$dob','$age','$gender','$race','$ethnicity') ON DUPLICATE KEY UPDATE Email='$email', PhoneNum='$phone', FirstName='$fn', MiddleName='$mn', LastName='$ln', PrefName='$pn', DOB='$dob', Age='$age', Gender='$gender', Race='$race', Ethnicity='$ethnicity';
        INSERT INTO EmergCont (EagleID,Name,Relation,PhoneNum,Email) VALUES ('$eagleid','$emergname','$emergrela','$emergphone','$emergemail') ON DUPLICATE KEY UPDATE Name='$emergname', Relation='$emergrela', PhoneNum='$emergphone', Email='$emergemail'; 
        INSERT INTO Passports (EagleID,PassFName,PassMName,PassLName,PassportVerif,PassportNatl,PassportNum,PassportExpDate) VALUES ('$eagleid','$passfn','$passmn','$passln','$passverif','$passnatl','$passnum','$passexpdate') ON DUPLICATE KEY UPDATE PassFName='$passfn', PassMName='$passmn', PassLName='$passln', PassportVerif='$passverif', PassportNatl='$passnatl', PassportNum='$passnum', PassportExpDate='$passexpdate';
        INSERT INTO Education (EagleID,Class,Major,GPA,CreditHours,AcademicStanding) VALUES ('$eagleid','$class','$major','$gpa','$cdh','$acstand') ON DUPLICATE KEY UPDATE Class='$class', Major='$major', GPA='$gpa', CreditHours='$cdh', AcademicStanding='$acstand';" . $query2;
        
    if(mysqli_multi_query($conn,$query)) {
	    echo "Update successful.";
	    echo "$query";
	}
	else {
	    echo "Update failed" . mysqli_error($conn) . "\n" . $query;
	}
}
?>
</body>
</html>