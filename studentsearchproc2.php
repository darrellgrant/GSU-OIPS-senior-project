<?php
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
?>
<html>
<head>
<style>
th, td, tr {
    border-bottom: 1px solid #ddd;
    padding: 5px;
    text-align:center;
    height:35px;
}

#searchleft {
    width:40%;
    float: left;
}

#searchmiddle {
    width:10%;
    float:left;
}

#searchmiddle,#searchleft {
    vertical-align:top;
}
#searchright {
    width:20%;
    float:right;
}

.overlay {
  height: 80%;
  width: 80%;
  display: none;
  position: fixed;
  z-index: 10;
  top: 0;
  left: 0;
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0, 0.9);
}

.modal {
  display: none; 
  position: fixed; 
  z-index: 1; 
  padding-top: 100px; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0.4); 
  overflow-y: scroll;
}

.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px groove #888;
  width: 90%;
  height:80%;
  overflow:auto;
}

iframe {
    height:100%;
    width:100%;
}
</style>
<script>
function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}
</script>
</head>
<body>
<?php
if (isset($_POST['Search'])) {
	//# Get POST data from Search
	$eagleid = $_POST['eid'];
	$fname = $_POST['fn'];
	$mname = $_POST['mn'];
	$lname = $_POST['ln'];
	$pname = $_POST['pn'];
	$email = $_POST['email'];
	if($_POST['email']!==""){
	    $email .= "@georgiasouthern.edu";
	}
	$age = $_POST['age'];
	$gender = $_POST['gen'];
	$race = $_POST['race'];
	$eth = $_POST['eth'];
	
	$educclass = $_POST['educclass'];
	$major = $_POST['major'];
	$gpa = $_POST['gpa'];
	$gpamod = $_POST['gpamod'];
	$credh = $_POST['credh'];
	$credhmod = $_POST['cdhmod'];
	$acstand = $_POST['acstand'];
	
	$progid = $_POST['progid'];
	$judver = $_POST['judver'];
	$lettersent = $_POST['letsent'];
	$enrollver = $_POST['enrver'];
	$gpaver = $_POST['gpaver'];

	//# Store POST data into arrays 
	$personalarr = array("EagleID",$eagleid,"FirstName",$fname,"MiddleName",$mname,"LastName",$lname,"PrefName",$pname,"Email",$email,"Age",$age,"Gender",$gender,"Race",$race,"Ethnicity",$eth);
	$educationarr = array("Class",$educclass,"Major",$major,"GPA",$gpa,"CreditHours",$credh,"AcademicStanding",$acstand);
	$applicationarr = array("ProgramID",$progid,"JudicialVerif",$judver,"LetterSent",$lettersent,"EnrollmentVerif",$enrollver,"GPAVerif",$gpaver);
	
	//# filter data to only have non empty values
	$personalarr = arrFix($personalarr);
	$educationarr = arrFix($educationarr);
	$applicationarr = arrFix($applicationarr);
    
    //# Build query string
    $qstr = "Select Student.EagleID,Student.FirstName,Student.LastName,Student.Email from Student";
    $pstr;
    //# if the first entry in arrays are not empty, then process array and append qstr
    if(count($personalarr)>0) {
        //# check other arrays to determine whether we save the $pstr for later or not
        if(count($educationarr)==0 && count($applicationarr)==0) {
	        $qstr .= " where " . buildStrs($personalarr,"Student","","");
        }
        else {
            $pstr .= " and " . buildStrs($personalarr,"Student","","");
        }
    }
    
    if(count($educationarr)>0) {
        $qstr .= " inner join Education on Student.EagleID=Education.EagleID and ";
        $qstr .= buildStrs($educationarr,"Education",$gpamod,$credhmod);
    }
     
    if(count($applicationarr)>0) {
        $qstr .= " inner join Application on Student.EagleID=Application.EagleID and ";
        $qstr .= buildStrs($applicationarr,"Application","","");
    }
    $qstr .= $pstr;
    //# check for an empty search
    if(trim($qstr)!="Select Student.EagleID,Student.FirstName,Student.LastName,Student.Email from Student") {
       //# perform query and count rows for user counting
        $query = mysqli_query($conn,$qstr);
        $query2 = mysqli_query($conn,$qstr);
        printf("Your search returned %d result(s)",mysqli_num_rows($query));
        ?>
        <br />
        <div id="container">
        <form method="post" action="emaillist.php" onsubmit="parent.searchCollapse()">
        <div id="searchright"><button type="submit" name="submit" value="Create Mailing list" onclick="parent.searchCollapse()">Create Mailing List from checked accounts</button></div>
        <div id="searchleft">
            <table>
                <tr><th></th>
        	    <th><b>EagleID</b></th>
        	    <th><b>First Name</b></th>
        	    <th><b>Last Name</b></th>
        	    <th><b>Email</b></th>
        	    <?php 
        	    //# display information from search, and collect a list[] of emails in case of mailing list
        	    while($myrow = mysqli_fetch_array($query)) {
        	    ?>
        	        <tr>
        	            <td><input type="checkbox" checked name="list[]" value="<?php echo $myrow['Email'] ?>"></td>
        	            <td><?php echo $myrow['EagleID'] ?></td>
        	            <td><?php echo $myrow['FirstName'] ?></td>
        	            <td><?php echo $myrow['LastName'] ?></td>
        	            <td><?php echo $myrow['Email'] ?></td>
        	       </tr>
                <?php }
        	    ?>
	        </table>
	    </div>
	    </form>
	    <div id="searchmiddle">
	    <table>
	    <th><b>Edit</b></th>
	    <?php
	        while($myrow = mysqli_fetch_array($query2)) { ?>
	            <form target="popupiframe" action="viewstudent.php" method="post" onsubmit="openModal('editModal')">
	                   <tr>
	                        <td>
	                        <input type="hidden" name="eagleid" value="<?php echo $myrow['EagleID'] ?>">
	                        <button type="submit">Edit Profile</button>
	                        </td>
	                   </tr>
	           </form>
	        <?php 
	        } ?>
	   </table>
	   </div>
    <?php } 
    else { ?>
        <br /><h2>No inputs in search</h2>
    <?php } ?>
    </div>
<?php
}
//# function to filter array using arrFilter then return a new array of non-empty values
function arrFix(&$s){
    $p = array();
	$q = array();
	$count = count($s);
	//# Strip escape string, html tags, and excess whitespace
	for($i=1;$i<$count;$i+=2) {
	    $p[$i-1] = $s[$i-1];
	    $p[$i] = arrFilter($s[$i]);
	}
	//# Filter out empty entries and push non-empty pairs
	for($i=0;$i<$count;$i+=2){
		if($s[$i+1]!==""){
			array_push($q,$p[$i],$p[$i+1]);
		}
	}
	return $q;
}

//# filter the array of values with mysql escape strings, html filter, and trim excess whitespace
function arrFilter($s){
    $val1 = mysqli_real_escape_string($conn,$s);
	$val2 = filter_var($s,FILTER_SANITIZE_STRING);
	return trim($val2);
}

//# takes the filtered value array and builds the "where" string
function buildStrs(&$s,$t,$gpamod,$credhmod){
    $colstr;
	//# go through array
	$ct = count($s);
	for($i=0;$i<$ct;$i+=2){
	    //# check for non-empty GPA and CreditHours array entries
	    if($s[$i]=="GPA" || $s[$i]=="CreditHours") {
	        if($s[$i]=="GPA") {
	            $mod = $gpamod;
	        }
	        else {
	            $mod = $credhmod;
	        }
	        //# build the string with the appropriate comparator modifier
	        $colstr .= $t . "." . $s[$i] . $mod . "'" . $s[$i+1] . "' ";
	    }
	    else {
    	    //# append (table).(column)='(value)'
    	    $colstr .= $t . "." . $s[$i] . "='" . $s[$i+1] . "' ";
    	    if($i < $ct-2){
    	        $colstr .= "and ";
    	    }
	    }
	}
	return $colstr;
}
?>
<div id="editModal" class="modal">
    <div class="modal-content">
        <iframe id="popupiframe" name="popupiframe" frameborder="0" scrolling="yes"></iframe>
    </div>
</div>
</body>
<script>
function openModal(tar) {
  var modal = document.getElementById(tar);
  modal.style.display = "block";
}

function closeModal(tar) {
  var modal = document.getElementById(tar);
  modal.style.display = "none";
}
</script>
</html>
