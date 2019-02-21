<!DOCTYPE html>
<?php
//# bugs/fixes: 1) echoed Courses do not exclude ones already saved to the Program entry
//#             2) page refresh on modal load is not ideal
//#             3) textarea is not aligned with plaintext
//#             4) Director selection not done, waiting on Login system tables, so query functions are set to 0
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
include $_SERVER['DOCUMENT_ROOT'] . "/sc/countrieslist.php";
include $_SERVER['DOCUMENT_ROOT'] . "/sc/semesterlist.php";
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}

div#left {
    float:left;
    position:absolute;
    z-index:-1;
    width:95%;
    display:inline-block;
}

div#right {
    width:10%;
    display:inline-block;
    position:absolute;
    z-index:0;
    right:10%;
}

div.programcontent {
    float:left;
    width:75%;
}

div.information {
    width:25%;
    float:left;
}

.collapsible {
  background-color: #777;
  color: white;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.active, .collapsible:hover {
  background-color: #555;
}

.content {
  padding: 0 18px;
  display: none;
  overflow: auto;
  background-color: #f1f1f1;
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
  width: 80%;
  height:80%;
  overflow:auto;
}

.close,
.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
  position:relative;
  left:20%;
  background-color: red;
  border: 1px solid red;
  color: white;
  padding: 10px 24px;
  font-size: 12px;
}

.save,
.save:hover,
.save:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
  background-color: green;
  border: 1px solid green;
  color: white;
  padding: 12px 28px;
  font-size: 14px;
  position:relative;
  left:15%;
}

.add {
    font-size:14px;
}

fieldset {
    display:table;
}

fieldset > input {
    width:100%;
}

fieldset * {
    margin-bottom:8px;
}

.column2 {
    display:table-cell;
    margin-left: 6px;
    padding:4px;
}

.column {
    display:table-cell;
    text-align:right;
    width: 15%;
    padding:4px;
}

.row {
    display:table-row;
}

.columnlong {
    display:table-cell;
    text-align:right;
    width:25%;
    padding:4px;
}

.column2long {
    display:table-cell;
    width: 260px;
    margin-left:6px;
    padding:4px;
}

input.long {
    width:80%;
}

input.short {
    width:40%;
}

.column2short {
    display:table-cell;
    width: 5%;
    margin-left: 6px;
    padding:4px;
}


table {
    border-collapse: collapse; 
    margin: 5%;
    margin-top:2%;
}

th, td, td > input {
    text-align: center;
    padding: 4px; 
}

table tr {
    border-bottom: 1px solid #ddd; 
}
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#edepamt,#ep1amt,#ep2amt,#ep3amt,#ep4amt,#einsamt").on("ready blur focus submit",function(){
    var bal = 0;
    var arr = [parseFloat($("#edepamt").val()),"#edepamt",parseFloat($("#ep1amt").val()),"#ep1amt",parseFloat($("#ep2amt").val()),"#ep2amt",parseFloat($("#ep3amt").val()),"#ep3amt",parseFloat($("#ep4amt").val()),"#ep4amt",parseFloat($("#einsamt").val()),"#einsamt"];
    for(i=0;i<12;i+=2) {
        if(arr[i] >= 0) {
            bal += arr[i];
        }
        else {
            $(i+1).val(0);
        }
    }
    $("#ebal,#ebalh").attr("value",bal);
  });
  $("#adepamt,#ap1amt,#ap2amt,#ap3amt,#ap4amt,#ainsamt").on("ready blur focus",function(){
    var bal = 0;
    var arr = [parseFloat($("#adepamt").val()),"#adepamt",parseFloat($("#ap1amt").val()),"#ap1amt",parseFloat($("#ap2amt").val()),"#ap2amt",parseFloat($("#ap3amt").val()),"#ap3amt",parseFloat($("#ap4amt").val()),"#ap4amt",parseFloat($("#ainsamt").val()),"#ainsamt"];
    for(i=0;i<12;i+=2) {
        if(arr[i] >= 0) {
            bal += arr[i];
        }
        else {
            $(i+1).val(0);
        }
    }
    $("#abal,#abalh").attr("value",bal);
  });
  $("#eprogramstartdate").on("ready blur focus",function(){
    var date = $("#eprogramstartdate").val();
    var pattern = /[2][0][0-9][0-9]/;
    var result = date.match(pattern);
    $("#eprogramyear").val(result);
  });
  $("#aprogramstartdate").on("ready blur focus",function(){
    var date = $("#aprogramstartdate").val();
    var pattern = /[2][0][0-9][0-9]/;
    var result = date.match(pattern);
    $("#aprogramyear").val(result);
  });
});
</script>
</head>
<body>
<?php
if(isset($_POST['addsubmit'])) {
    //# build the query
    $postarr = array($_POST['programname'],$_POST['programcountry'],$_POST['directorsel'],$_POST['semester'],$_POST['programstartdate'],$_POST['programenddate'],$_POST['programyear'],$_POST['suggestedmajors'],$_POST['ac1'],$_POST['ac2'],$_POST['ac3'],$_POST['ac4'],$_POST['ac5'],$_POST['ac6'],$_POST['ac7'],$_POST['ac8'],$_POST['ac9'],$_POST['ac10'],$_POST['ac11'],$_POST['ac12'],$_POST['ac13'],$_POST['ac14'],$_POST['ac15'],$_POST['miscf1'],$_POST['miscf2'],$_POST['miscf3'],$_POST['miscf4'],$_POST['miscf5'],$_POST['miscf6'],$_POST['miscf7'],$_POST['miscf8'],$_POST['miscf9'],$_POST['miscf10'],$_POST['bal'],$_POST['depdate'],$_POST['depamt'],$_POST['p1date'],$_POST['p1amt'],$_POST['p2date'],$_POST['p2amt'],$_POST['p3date'],$_POST['p3amt'],$_POST['p4date'],$_POST['p4amt'],$_POST['insdate'],$_POST['insamt']);
    //# filtering functions to come
    
    $query = "INSERT INTO Program (ProgramName,Country,DirectorID,Semester,ProgramStartDate,ProgramEndDate,Year,SuggestedMajors,AvailCourse1,AvailCourse2,AvailCourse3,AvailCourse4,AvailCourse5,AvailCourse6,AvailCourse7,AvailCourse8,AvailCourse9,AvailCourse10,AvailCourse11,AvailCourse12,AvailCourse13,AvailCourse14,AvailCourse15,MiscField1Name,MiscField2Name,MiscField3Name,MiscField4Name,MiscField5Name,MiscField6Name,MiscField7Name,MiscField8Name,MiscField9Name,MiscField10Name,BalanceDue,DepositDateDue,DepositAmount,Payment1DateDue,Payment1Amt,Payment2DateDue,Payment2Amt,Payment3DateDue,Payment3Amt,Payment4DateDue,Payment4Amt,InsuranceDateDue,InsuranceAmt) VALUES ('$postarr[0]','$postarr[1]','0','$postarr[3]','$postarr[4]','$postarr[5]','$postarr[6]','$postarr[7]','$postarr[8]','$postarr[9]','$postarr[10]','$postarr[11]','$postarr[12]','$postarr[13]','$postarr[14]','$postarr[15]','$postarr[16]','$postarr[17]','$postarr[18]','$postarr[19]','$postarr[20]','$postarr[21]','$postarr[22]','$postarr[23]','$postarr[24]','$postarr[25]','$postarr[26]','$postarr[27]','$postarr[28]','$postarr[29]','$postarr[30]',,'$postarr[31]','$postarr[32]','$postarr[33]','$postarr[34]','$postarr[35]','$postarr[36]','$postarr[37]','$postarr[38]','$postarr[39]','$postarr[40]','$postarr[41]','$postarr[42]','$postarr[43]','$postarr[44]','$postarr[45]','$postarr[46]')"; 


    if($mysqli_query($conn,$query)) {
        
    }
    else {
        echo mysqli_error($conn);
    }
}

//# a Program UPDATE
if(isset($_POST['editsubmit'])) {
    //# start building the $query string
    $query = "UPDATE Program SET ProgramName='$_POST[programname]',Country='$_POST[programcountry]',DirectorID='0',Semester='$_POST[semester]', ProgramStartDate='$_POST[programstartdate]', ProgramEndDate='$_POST[programenddate]', Year='$_POST[programyear]', SuggestedMajors='$_POST[majors]', ";
    //# append non-empty AvailCourse parts
    for($i=1;$i<16;$i++) {
        $temp1 = "AvailCourse" . $i;
        $temp2 = "ac" . $i;
        if(strlen($_POST[$temp2])>0){
            $query .=  $temp1 . "='" . $_POST[$temp2] . "', ";
        }
    }
    //# append non-empty Miscellaneous Field Names
    for($i=1;$i<11;$i++) {
        $temp1 = "MiscField" . $i . "Name";
        $temp2 = "miscf" . $i;
        if(strlen($_POST[$temp2])>0) {
            $query .= $temp1 . "='" . $_POST[$temp2] . "', ";
        }
    }
    $query .= "BalanceDue='$_POST[bal]',DepositDateDue='$_POST[depdate]', DepositAmount='$_POST[depamt]', Payment1DateDue='$_POST[p1date]', Payment1Amt='$_POST[p1amt]', Payment2DateDue='$_POST[p2date]', Payment2Amt='$_POST[p2amt]', Payment3DateDue='$_POST[p3date]', Payment3Amt='$_POST[p3amt]', Payment4DateDue='$_POST[p4date]', Payment4Amt='$_POST[p4amt]', InsuranceDateDue='$_POST[insdate]', InsuranceAmt='$_POST[insamt]' where ProgramID='$_POST[pid]'";
    
    if(mysqli_query($conn,$query)) {
        
    }
    else {
        echo mysqli_error($conn);
    }
}

if(isset($_POST['delsubmit'])) {
    $query = "Delete from Program where ProgramID='$_POST[deleteid]'";
    if(mysqli_query($conn,$query)) {
        
    }
    else {
        echo mysqli_error($conn);
    }
}
?>
<div>
<div id="left">
<table>
    <tr>
    <th>Program ID</th>
    <th>Program Name</th>
    <th>Director</th>
    <th></th>
    <th></th>
    </tr>
<?php 
$programq = mysqli_query($conn,"Select ProgramID,ProgramName,DirectorID from Program");
while ($programr = mysqli_fetch_array($programq)) { ?>
<tr>
    <td class="id"><?php echo $programr['ProgramID'] ?></td>
    <td><?php echo $programr['ProgramName'] ?></td>
    <td><?php echo $programr['DirectorID'] ?></td>
    <td><form method="post" action="">
        <input type="hidden" id="edit" name="editid" value="<?php echo $programr['ProgramID'] ?>">
        <button type="submit" name="editopen" value="Edit Program" onclick="openModal('editModal');closeModal('addModal');">Edit</button>
    </form></td>
    <td><form method="post" action="" onsubmit="return confirm('Delete this entry? \n<?php echo "Program ID: " . $programr['ProgramID'] ?>\n<?php echo "Program Name: " . $programr['ProgramName'] ?>')">
        <input type="hidden" id="delete" name="deleteid" value="<?php echo $programr['ProgramID'] ?>">
        <input type="submit" name="delsubmit" value="Remove Program" >
    </form></td>
</tr>
<?php } ?>
</table>

<div id="editModal" class="modal">
    <div class="modal-content">
    <?php
    //# retreive Program information from submitted ProgramID from POST[editid]
    $pid = $_POST['editid'];
    $q = mysqli_query($conn,"Select * From Program where ProgramID='$pid'");
    $r = mysqli_fetch_array($q);
    ?>
    <form method="post" action="">
    <p>Editing <b><?php echo $r['ProgramName'] ?></b>
        <button type="submit" class="save" name="editsubmit" value="save">Save Changes</button>
        <button type="button" class="close" name="cancel" onclick="closeModal('editModal')">Cancel</button></p>
    <input type="hidden" name="pid" value="<?php echo $pid ?>">
    <fieldset>
        <div class="row">
            <div class="column">Program Name: </div>
            <div class="column2"><input type="text" required name="programname" value="<?php echo $r['ProgramName'] ?>"></div>
            <div class="column">Country: </div>
            <div class="column2"><input class="long" list="countries" id="programcountry" name="programcountry" value="<?php echo $r['Country']?>">
                <datalist id="countries">
				<?php 
				foreach($countries as $c) { ?>
				    <option value="<?php echo $c ?>">
				<?php }	?>
			    </datalist>
			</div>
			<div class="column">Director: </div>
            <div class="column2">
                <select id="directorsel" name="directorsel">
                
                </select></div>
		</div>
		<div class="row">
            <div class="column">Semester: </div>
            <div class="column2">
                <select id="semestersel" name="semester">
                    <option value="<?php echo $r['Semester'] ?>"> <?php echo $r['Semester'] ?>
                    <?php
                    foreach ($semesterlist as $s) { 
                        if($s != $r['Semester']) { ?>
                      <option value="<?php echo $s ?>"> <?php echo $s ?>  
                    <?php 
                        } 
                    } ?>
                </select>
            </div>
            <div class="columnlong">Program Start Date: </div>
            <div class="column2short"><input type="date" id="eprogramstartdate" name="programstartdate" placeholder="YYYY-MM-DD" value="<?php echo $r['ProgramStartDate'] ?>"></div>
            <div class="columnlong">Program End Date: </div>
            <div class="column2short"><input type="date" name="programenddate" placeholder="YYYY-MM-DD" value="<?php echo $r['ProgramEndDate'] ?>"></div>
        </div>
        <div class="row" style="vertical-align:middle">    
            <div class="column">Year: </div>
            <div class="column2short"><input pattern="[2][0][0-9]{2}" id="eprogramyear" name="programyear" value="<?php echo $r['Year'] ?>"></div>
            <div class="column"><p>Suggested Majors: </p></div>
            <textarea rows="2" columns="50" name="majors"><?php echo $r['SuggestedMajors'] ?></textarea>
        </div>
        <hr>
        <button type="button" class="collapsible">Available Courses</button>
        <div class="content">
        <?php 
        //# get all Class information
        $cq = mysqli_query($conn,"Select CourseID,Course,CourseName from Classes Order By Course");
        $idarr = array();
        $coursearr = array();
        $coursenamearr = array();
        $ct;
        
        //# store Class information in individual arrays
        while($cr = mysqli_fetch_array($cq)) {
            array_push($idarr,$cr['CourseID']);
            array_push($coursearr,$cr['Course']);
            array_push($coursenamearr,$cr['CourseName']);
            $ct++;
        }
        //# search $idarr for CourseIDs already in the Program entry and store their Course in $existarr 
        $existarr = array();
        for($i=1;$i<16;$i++) {
            $temp = "AvailCourse" . $i;
            if(strlen($r[$temp])>0) {
                $found = array_search($r[$temp],$idarr);
                array_push($existarr,$coursearr[$found],$coursenamearr[$found]);
            }
        }
        $extemp = 0;
        //# $i tracks the current row
        for($i=1;$i<16;$i++) {
            $temp = "AvailCourse" . $i;
            
        ?>
        <div class="row">
            <div class="columnlong">Available Course <?php echo $i ?>:</div>
            <div class="column2">
                <select id="ac<?php echo $i ?>" name="ac<?php echo $i ?>">
                    <option value="<?php echo $r[$temp] ?>"><?php echo $existarr[$extemp] . " - " . $existarr[$extemp+1];
                    for($j=0;$j<$ct;$j++) {
                        if(array_search($j,$existarr)>=0) {
                    ?>
                        <option value="<?php echo $idarr[$j] ?>"><?php echo $coursearr[$j] . " - " . $coursenamearr[$j];
                        ?>
                        <?php } 
                    }
                     $extemp+=2;
                     ?>
                </select><button type="button" onclick="resetClass('<?php echo $i ?>')">Clear</button>
            </div>
        </div>
        <?php } ?>
        </div>
        <button type="button" class="collapsible">Payment Information</button>
        <div class="content">
            <div class="programcontent">
            <div class="row">
                <div class="column">Deposit Amount:</div>
                <div class="column2"><input type="text" class="short" id="edepamt" name="depamt" placeholder="0000.00" value="<?php echo $r['DepositAmount'] ?>"></div>
                <div class="column">Deposit Due Date:</div>
                <div class="column2"><input type="date" name="depdate" placeholder="YYYY-MM-DD" value="<?php echo $r['DepositDateDue'] ?>"></div>
            </div>
            <div class="row">
                <div class="columnlong">Payment 1 Amount:</div>
                <div class="column2"><input type="text" class="short" id="ep1amt" name="p1amt" placeholder="0000.00" value="<?php echo $r['Payment1Amt'] ?>"></div>
                <div class="columnlong">Payment 1 Due Date:</div>
                <div class="column2"><input type="date" name="p1date" placeholder="YYYY-MM-DD" value="<?php echo $r['Payment1DateDue'] ?>"></div>
            </div>
            <div class="row">
                <div class="columnlong">Payment 2 Amount:</div>
                <div class="column2"><input type="text" class="short" id="ep2amt" name="p2amt" placeholder="0000.00" value="<?php echo $r['Payment2Amt'] ?>"></div>
                <div class="columnlong">Payment 2 Due Date:</div>
                <div class="column2"><input type="date" name="p2date" placeholder="YYYY-MM-DD" value="<?php echo $r['Payment2DateDue'] ?>"></div>
            </div>
            <div class="row">
                <div class="columnlong">Payment 3 Amount:</div>
                <div class="column2"><input type="text" class="short" id="ep3amt" name="p3amt" placeholder="0000.00" value="<?php echo $r['Payment3Amt'] ?>"></div>
                <div class="columnlong">Payment 3 Due Date:</div>
                <div class="column2"><input type="date" name="p3date" placeholder="YYYY-MM-DD" value="<?php echo $r['Payment3DateDue'] ?>"></div>
            </div>
            <div class="row">
                <div class="columnlong">Payment 4 Amount:</div>
                <div class="column2"><input type="text" class="short" id="ep4amt" name="p4amt" placeholder="0000.00" value="<?php echo $r['Payment4Amt'] ?>"></div>
                <div class="columnlong">Payment 4 Due Date:</div>
                <div class="column2"><input type="date" name="p4date" placeholder="YYYY-MM-DD" value="<?php echo $r['Payment4DateDue'] ?>"></div>
            </div>
            <div class="row">
                <div class="columnlong">Insurance Amount:</div>
                <div class="column2"><input type="text" class="short" id="einsamt" name="insamt" placeholder="0000.00" value="<?php echo $r['InsuranceAmt'] ?>"></div>
                <div class="columnlong">Insurance Due Date:</div>
                <div class="column2"><input type="date" name="insdate" placeholder="YYYY-MM-DD" value="<?php echo $r['InsuranceDateDue'] ?>"></div>
            </div>
			</div>
            <div class="information">
                Total Balance: <input type="text" class="short" id="ebal" disabled value="<?php echo $r['BalanceDue'] ?>">
                <input type="hidden" id="ebalh" name="bal" value="">
            </div>
        </div>
        <button type="button" class="collapsible">Miscellaneous Fields</button>
        <div class="content">
            <?php 
            for($i=1;$i<11;$i++) {
                $temp = "MiscField" . $i . "Name";
            ?>
            <div class="row">
                <div class="columnlong">Miscellaneous Field <?php echo $i ?> Name:</div>
                <div class="column2long"><input class="long" type="text" name="miscf<?php echo $i ?>" value="<?php echo $r[$temp] ?>"></div>
            </div>
            <?php } ?>
        </div>
    </fieldset>
    </form>
  </div>
</div>

<div id="addModal" class="modal">
    <div class="modal-content">
    <form method="post" action="">
            <button type="submit" class="save"  name="addsubmit" value="Save Changes">Save Changes</button>
            <button type="button" class="close" name="cancel" onclick="closeModal('addModal')">Cancel</button>
    <fieldset>
        <div class="row">
            <div class="column">Program Name: </div>
            <div class="column2"><input type="text" required name="programname"></div>
            <div class="column">Country: </div>
            <div class="column2"><input class="long" list="countries" id="programcountry" name="programcountry">
                <datalist id="countries">
				<?php 
				foreach($countries as $c) { ?>
				    <option value="<?php echo $c ?>">
				<?php }	?>
			    </datalist>
			</div>
			<div class="column">Director: </div>
            <div class="column2">
                <select id="directorsel" name="directorsel">
                
                </select></div>
		</div>
		<div class="row">
            <div class="column">Semester: </div>
            <div class="column2">
                <select id="semestersel" name="semester">
                    <option value="">
                    <?php
                    foreach ($semesterlist as $s) {  ?>
                      <option value="<?php echo $s ?>"> <?php echo $s ?>  
                    <?php } ?>
                </select>
            </div>
        
            <div class="columnlong">Program Start Date: </div>
            <div class="column2short"><input type="date" id="aprogramstartdate" name="programstartdate" placeholder="YYYY-MM-DD"></div>
            <div class="columnlong">Program End Date: </div>
            <div class="column2short"><input type="date" name="programenddate" placeholder="YYYY-MM-DD"></div>
        </div>
        <div class="row" style="vertical-align:middle">    
            <div class="column">Year: </div>
            <div class="column2short"><input id="aprogramyear" pattern="[2][0][0-9]{2}" name="programyear"></div>
            <div class="column"><p>Suggested Majors: </p></div>
            <textarea rows="2" columns="50" name="majors"></textarea>
        </div>
        <hr>
        <button type="button" class="collapsible">Available Courses</button>
        <div class="content">
        <?php 
        //# get all Class information
        $cq = mysqli_query($conn,"Select CourseID,Course,CourseName from Classes Order By Course");
        $idarr = array();
        $coursearr = array();
        $coursenamearr = array();
        $ct;
        
        //# store Class information in individual arrays
        while($cr = mysqli_fetch_array($cq)) {
            array_push($idarr,$cr['CourseID']);
            array_push($coursearr,$cr['Course']);
            array_push($coursenamearr,$cr['CourseName']);
            $ct++;
        }
        //# $i tracks the current row
        for($i=1;$i<16;$i++) {
            $temp = "AvailCourse" . $i;
        ?>
        <div class="row">
            <div class="columnlong">Available Course <?php echo $i ?>:</div>
            <div class="column2">
                <select id="ac<?php echo $i ?>" name="ac<?php echo $i ?>">
                    <option value=""><?php echo " - ";
                    for($j=0;$j<$ct;$j++) {
                    ?>
                        <option value="<?php echo $idarr[$j] ?>"><?php echo $coursearr[$j] . " - " . $coursenamearr[$j];
                     } 
                     $extemp+=2;
                     ?>
                </select>
            </div>
        </div>
        <?php } ?>
        </div>
        <button type="button" class="collapsible">Payment Information</button>
        <div class="content">
        <div class="programcontent">
			<div class="row">
                <div class="column">Deposit Amount:</div>
                <div class="column2"><input type="text" id="adepamt" class="short" name="depamt" placeholder="0000.00"></div>
                <div class="column">Deposit Due Date:</div>
                <div class="column2"><input type="date" name="depdate" placeholder="YYYY-MM-DD"></div>
            </div>
            <div class="row">
                <div class="columnlong">Payment 1 Amount:</div>
                <div class="column2"><input type="text" id="ap1amt" class="short" name="p1amt" placeholder="0000.00"></div>
                <div class="columnlong">Payment 1 Due Date:</div>
                <div class="column2"><input type="date" name="p1date" placeholder="YYYY-MM-DD"></div>
            </div>
            <div class="row">
                <div class="columnlong">Payment 2 Amount:</div>
                <div class="column2"><input type="text" id="ap2amt" class="short" name="p2amt" placeholder="0000.00"></div>
                <div class="columnlong">Payment 2 Due Date:</div>
                <div class="column2"><input type="date" name="p2date" placeholder="YYYY-MM-DD"></div>
            </div>
            <div class="row">
                <div class="columnlong">Payment 3 Amount:</div>
                <div class="column2"><input type="text" id="ap3amt" class="short" name="p3amt" placeholder="0000.00"></div>
                <div class="columnlong">Payment 3 Due Date:</div>
                <div class="column2"><input type="date" name="p3date" placeholder="YYYY-MM-DD"></div>
            </div>
            <div class="row">
                <div class="columnlong">Payment 4 Amount:</div>
                <div class="column2"><input type="text" id="ap4amt" class="short" name="p4amt" placeholder="0000.00"></div>
                <div class="columnlong">Payment 4 Due Date:</div>
                <div class="column2"><input type="date" name="p4date" placeholder="YYYY-MM-DD"></div>
            </div>
            <div class="row">
                <div class="columnlong">Insurance Amount:</div>
                <div class="column2"><input type="text" id="ainsamt" class="short" name="insamt" placeholder="0000.00"></div>
                <div class="columnlong">Insurance Due Date:</div>
                <div class="column2"><input type="date" name="insdate" placeholder="YYYY-MM-DD"></div>
            </div>
        <div class="information">
                Total Balance: <input type="text" class="short" id="abal" disabled value="<?php echo $r['BalanceDue'] ?>">
                <input type="hidden" id="abalh" name="bal" value="">
        </div>
        </div>
        </div>
        <button type="button" class="collapsible">Miscellaneous Fields</button>
        <div class="content">
            <?php 
            for($i=1;$i<11;$i++) {
                $temp = "MiscField" . $i . "Name";
            ?>
            <div class="row">
                <div class="columnlong">Miscellaneous Field <?php echo $i ?> Name:</div>
                <div class="column2long"><input class="long" type="text" name="miscf<?php echo $i ?>"></div>
            </div>
            <?php } ?>
        </div>
        </div>
    </fieldset>
</form>
</div>
</div>
<div id="right">
    <button type="button" class="add" onclick="openModal('addModal');closeModal('editModal');">Add New Program</button>
</div>
</div>
<script>
function resetClass(n) {
    var str = "ac" + n;
    document.getElementById(str).value="";
}
function openModal(tar) {
  var modal = document.getElementById(tar);
  modal.style.display = "block";
}

function closeModal(tar) {
    var modal = document.getElementById(tar);
  modal.style.display = "none";
}

<?php 
if(isset($_POST['editid'])) {
    ?> openModal('editModal'); <?php
}
?>
</script>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>
</body>
</html>
