<html>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
include $_SERVER['DOCUMENT_ROOT'] . "/sc/countrieslist.php";
?>
<head>
<style>
fieldset {
    display:table;
}
.column {
    display:table-cell;
    width: 160px;
}

.column2 {
    display:table-cell;
    text-align:right;
    width: 140px;
}

.columnlong {
    display:table-cell;
    width: 260px;
}

.column2med {
    display:table-cell;
    text-align:right;
    width: 200px;
}

.column2long {
    display:table-cell;
    text-align:right;
    width: 320px;
}

div.row {
    display:table-row;
}

input.long {
    width:240px;
}

input.short {
    width:60px;
}

input.datelength {
    width:80px;
}

input.datetime {
    width:140px;
}
input, select, textarea {
    padding: 4px 4px;
    margin: 4px 4px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
  width:20%;
}

#inputarea2 .tab button {
    width:33%;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 12px 12px;
  border: 1px solid #ccc;
  border-top: none;
}

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

.save,
.save:hover,
.save:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
  position:fixed;
  top:5px;
  right:120px;
  background-color: green;
  border: 1px solid green;
  color: white;
  padding: 12px 28px;
  font-size: 14px;
}


.collapsible {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 8px;
  width: 100%;
  border: none;
  text-align: center;
  outline: none;
  display: hidden;
  margin-left: auto;
  margin-right: auto;
  font-size: 15px;
}

/* Style the collapsible content. Note: hidden by default */
.collapsecontent {
  padding: 0 18px;
  display: none;
  overflow: hidden;
  background-color: #f1f1f1;
  position:relative;
  z-index:9999;
}

.active, .collapsible:hover {
  background-color: #f2f2f2;
  color: #000000;
  display:inline-block;
}

.segcollapse {
    width:30%;
    float:left;
    margin:1.66%;
}

</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("input,textarea,select").change(function(){
    $(this).css("background-color", "#5F9EA0");
  });
  $("#deppaid,#p1paid,#p2paid,#p3paid,#p4paid,#insurpaid").on("change",function(){
    var balance = 0;
    var due = parseFloat($("#fulldue").val());
    var arr = [parseFloat($("#depamt").val()),$("#deppaid").val(),parseFloat($("#p1amt").val()),$("#p1paid").val(),parseFloat($("#p2amt").val()),$("#p2paid").val(),parseFloat($("#p3amt").val()),$("#p3paid").val(),parseFloat($("#p4amt").val()),$("#p4paid").val(),parseFloat($("#insamt").val()),$("#insurpaid").val()];
    
    for(var i=0;i<12;i+=2) {
        if(arr[i+1] == "Yes") {
            balance += arr[i];
        }
    }
    
    $("#bal").val(due - balance);
  });
});
</script>
</head>
<body>
<?php
	$eagleid = $_POST['eagleid'];
    $personalquery = mysqli_query($conn,"Select * From Student where EagleID='$eagleid'");
    $personalrow = mysqli_fetch_array($personalquery);
	$emergquery = mysqli_query($conn,"Select * From EmergCont where EagleID='$eagleid'");
    $emergrow = mysqli_fetch_array($emergquery);
	$passportquery = mysqli_query($conn,"Select * From Passports where EagleID='$eagleid'");
    $passportrow = mysqli_fetch_array($passportquery);
    $educationquery = mysqli_query($conn,"Select * From Education where EagleID='$eagleid'");
    $educationrow = mysqli_fetch_array($educationquery);
    $applicationquery = mysqli_query($conn,"Select * From Application where EagleID='$eagleid'");
    $applicationrow = mysqli_fetch_array($applicationquery);
    
    $programrow;
    if($applicationrow['ProgramID'] > 0) {
        $programquery = mysqli_query($conn,"Select * From Program where ProgramID='$applicationrow[ProgramID]'");
        $programrow = mysqli_fetch_array($programquery);
    }
    //# display student information and allow for updating
    ?>
    
    <button class="close" onclick="parent.closeModal('editModal')">Cancel</button>
    <h2>Editing <?php echo $eagleid . " - " . $personalrow['FirstName'] . " " . $personalrow['LastName'] ?></h2>
    <div class="tab">
      <button class="tablinks" onclick="openTab(event, 'Personal')">Personal</button>
      <button class="tablinks" onclick="openTab(event, 'Emergency')">Emergency Contact</button>
      <button class="tablinks" onclick="openTab(event, 'Passport')">Passport</button>
      <button class="tablinks" onclick="openTab(event, 'Education')">Education</button>
      <button class="tablinks" onclick="openTab(event, 'Application')">Application</button>
    </div>
    <form id="savechange" action="updatestudent.php" method="POST" onsubmit="parent.closeModal('editModal')">
    <div class="inputarea">  
		<div id="Personal" class="tabcontent">
			<fieldset>
				<div class="row">
				    <div class="column2">EagleID: </div>
				    <div class="column"><input type="number" min="900000000" max="999999999" name="eid" value="<?php echo $eagleid ?>" required></div>
				    <div class="column2">Email: </div>
				    <div class="columnlong"><input type="email" class="long" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?php echo $personalrow['Email']?>" required></div>
				    <div class="column2">Phone Number: </div>
				    <div class="column"><input type="tel" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?php echo $personalrow['PhoneNum']?>" ></div>
				</div>
				<div class="row">
				    <div class="column2">First Name: </div> 
				    <div class="column"><input type="text" name="fn" value="<?php echo $personalrow['FirstName']?>" pattern="[a-zA-Z0-9\s]+"></div>
				    <div class="column2">Middle Name: </div>
				    <div class="column"><input type="text" name="mn" value="<?php echo $personalrow['MiddleName']?>" pattern="[a-zA-Z0-9\s]+"></div>
				    <div class="column2">Last Name: </div>
				    <div class="column"><input type="text" name="ln" value="<?php echo $personalrow['LastName']?>" pattern="[a-zA-Z0-9\s]+"></div>
				</div>
				<div class="row">
				    <div class="column2">Preferred Name: </div>
				    <div class="column"><input type="text" name="pn" value="<?php echo $personalrow['PrefName']?>"pattern="[a-zA-Z0-9\s]+"></div>
				    <div class="column2">DoB: </div>
				    <div class="column"><input type="date" name="dob" value="<?php echo $personalrow['DOB']?>"></div>
				    <div class="column2">Age: </div>
				    <div class="column"><input class="short" type="number" min="10" max="120" name="age" value="<?php echo $personalrow['Age']?>" pattern=[0-9]{2,3}></div>
				</div>
				<div class="row">
				    <div class="column2">Gender: </div>
				    <div class="column"><input type="text" name="gen" value="<?php echo $personalrow['Gender']?> " pattern="[a-zA-Z\s]+"></div>
				    <div class="column2">Race: </div>
				    <div class="column"><input type="text" name="race" value="<?php echo $personalrow['Race']?>" pattern="[a-zA-Z\s]+"></div>
				    <div class="column2">Ethnicity: </div>
				    <div class="column"><input type="text" name="eth" value="<?php echo $personalrow['Ethnicity']?>" pattern="[a-zA-Z\s]+"></div>
				</div>
			</fieldset>
		</div>
		<div id="Emergency" class="tabcontent">
			<fieldset>
			    <div class="row">
				    <div class="column2med">Name of Emergency Contact: </div>
				    <div class="column"><input type="text" name="emergname" value="<?php echo $emergrow['Name']?>" pattern="[a-zA-Z0-9\s]+"></div>
				    <div class="column2">Relation: </div>
				    <div class="column"><input type="text" name="emergrela" value="<?php echo $emergrow['Relation']?>" pattern="[a-zA-Z0-9\s]+"></div>
				    </div>
				<div class="row">
				    <div class="column2">Phone Number: </div>
				    <div class="column"><input type="tel" name="emergphone" value="<?php echo $emergrow['PhoneNum']?>"></div>
				    <div class="column2">Email: </div> 
				    <div class="columnlong"><input type="email" class="long" name="emergemail" value="<?php echo $emergrow['Email']?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"></div>
				</div>
			</fieldset>
		</div>
		<div id="Passport" class="tabcontent">
			<fieldset>
			    <div class="row">
				    <div class="column2">Passport Verified: </div>
				    <div class="column">
				        <select id="passverif" name="passverif">
							<option value="<?php echo $passportrow['PassportVerif'] ?>"><?php echo $passportrow['PassportVerif'] ?> 
							    <?php 
								$ppvarr = array("Yes","No");
							    foreach($ppvarr as $c) { 
							        if($c != $passportrow['PassportVerif']) { ?>
							            <option value="<?php echo $c ?>"><?php echo $c ?>
							        <?php } 
							    } ?>
					    </select>
					</div>
				</div>
				<div class="row">
    				<div class="column2">First name on passport: </div>
    				<div class="column"><input type="text" name="passfn" value="<?php echo $passportrow['PassFName']?>" pattern="[a-zA-Z0-9\s]+"></div>
    				<div class="column2med">Middle name on passport: </div>
    				<div class="column"><input type="text" name="passmn" value="<?php echo $passportrow['PassMName']?>" pattern="[a-zA-Z0-9\s]+"></div>
				</div>
				<div class="row">
				    <div class="column2med">Last name on passport: </div>
    				<div class="column"><input type="text" name="passln" value="<?php echo $passportrow['PassLName']?>" pattern="[a-zA-Z0-9\s]+"></div>
				    <div class="column2">Passport Nationality: </div>
				    <div class="columnlong"><input class="long" list="countries" id="passnatl" name="passnatl" pattern="[^\x22|\x3B]+" value="<?php echo $passportrow['PassportNatl']?>">
				        <datalist id="countries">
				            <?php 
				                foreach($countries as $c) { ?>
				                    <option value="<?php echo $c ?>"><?php echo $c ?>
				                <?php }
				            ?>
				        </datalist>
				    </div>
				</div>
				<div class="row">
				    <div class="column2">Passport Number: </div>
				    <div class="column"><input type="text" name="passnum" value="<?php echo $passportrow['PassportNum']?>" pattern="[^\x22|\x3B]+"></div>
				    <div class="column2med">Passport Expiration Date: </div>
				    <div class="column"><input type="date" name="passexp" value="<?php echo $passportrow['PassportExpDate']?>"></div>
				</div>
			</fieldset>
		</div>
		<div id="Education" class="tabcontent">
			<fieldset>
			    <div class="row">
    				<div class="column2">Class: </div>
    				<div class="column">
    				    <select id="educclass" name="educclass">
    						<option value="<?php echo $educationrow['Class'] ?>"><?php echo $educationrow['Class']?>
							<?php
    						$classarr = array("Freshman","Sophomore","Junior","Senior","Graduate","Other");
    						foreach($classarr as $c) {
    							if($c != $educationrow['Class']) { ?>
    								<option value="<?php echo $c ?>"><?php echo $c ?>
    						    <?php }
    						} ?>
    					</select>
					</div>
    				<div class="column2">Major: </div>
    				<div class="column"><input type="text" class="long" name="major" value="<?php echo $educationrow['Major'] ?>" pattern="[^\x22|\x3B]+"></div>
    			</div>
    			<div class="row">
    				<div class="column2">GPA: </div>
    				<div class="column"><input class="short" type="text" name="gpa" value="<?php echo $educationrow['GPA'] ?>"></div>
    				<div class="column2">Credit Hours: </div>
    				<div class="column"><input class="short" type="number" min="0" max="300" name="cdh" value="<?php echo $educationrow['CreditHours'] ?>"></div>
    			</div>
				<div class="row">
    				<div class="column2med">Good Academic Standing: </div>
    				<div class="column">
    				    <select id="acstand" name="acstand">
    						<option value="<?php echo $educationrow['AcademicStanding'] ?>"><?php echo $educationrow['AcademicStanding'] ?>
    						    <?php
								$easarr = array("Yes","No");
    						    foreach($easarr as $c) {
    						        if($c != $educationrow['AcademicStanding']) { ?>
    						            <option value="<?php echo $c ?>"><?php echo $c ?>
    						        <?php }
    						    } ?>
						</select>
					</div>
				</div>
			</fieldset>
		</div>
		<div id="Application" class="tabcontent">
			<fieldset>
			    <input type="hidden" name="appid" value="<?php echo $applicationrow['AppID'] ?>">
			    <div class="row">
    			    <div class="column2">GPA Verification: </div>
    				<div class="column">
    				    <select id="gpaverif" name="gpaverif">
    						<option value="<?php echo $applicationrow['GPAVerif'] ?>"><?php echo $applicationrow['GPAVerif'] ?>
    						<?php
							$arr = array("Yes","No");
    						foreach($arr as $c) {
    						    if($c != $applicationrow['GPAVerif']) { ?>
    						    	<option value="<?php echo $c ?>"><?php echo $c ?>
    					    	<?php }
    						} ?>
    					</select>
					</div>
				</div>
				<div class="row">
    				<div class="column2">Judicial Verification: </div>
    				<div class="column">
    				    <select id="judverif" name="judverif">
    						<option value="<?php echo $applicationrow['JudicialVerif'] ?>"><?php echo $applicationrow['JudicialVerif'] ?>
    							<?php
								$arr = array("Yes","No","Open");
    							foreach($arr as $c) {
    							    if($c != $applicationrow['JudicialVerif']) { ?>
    									<option value="<?php echo $c ?>"><?php echo $c ?>
    								<?php }
    							} ?>
    				    </select>
    				</div>
    				<div class="column2">Judicial Verification Note: </div>        
    				<div class="column"><textarea rows="4" cols="40" name="judverifnote" style="vertical-align:top;"><?php echo $applicationrow['JudicialVerifNote'] ?></textarea>
    				</div>
				</div>
				<div class="row">
    				<div class="column2">Letter Sent: </div>
    				<div class="column">
    				    <select id="letsent" name="letsent">
    						<option value="<?php echo $applicationrow['LetterSent'] ?>"><?php echo $applicationrow['LetterSent'] ?>
    							<?php
								$arr = array("Yes","No");
    							foreach($arr as $c) {
    								if($c != $applicationrow['LetterSent']) { ?>
    									<option value="<?php echo $c ?>"><?php echo $c ?>
    								<?php }
    							} ?>
    					</select>
    				</div>
    				<div class="column2med">Enrollment Verification: </div>
    				<div class="column">
    				    <select id="enrollverif" name="enrollverif">
    						<option value="<?php echo $applicationrow['EnrollmentVerif'] ?>"><?php echo $applicationrow['EnrollmentVerif'] ?>
    							<?php
								$arr = array("Yes","No");
    							foreach($arr as $c) {
    					    		if($c != $applicationrow['EnrollmentVerif']) { ?>
										<option value="<?php echo $c ?>"><?php echo $c ?>
    									<?php }
    								} ?>
    					</select>
    				</div>
				</div>
				<br>
				<div class="segcollapse">
				    <button type="button" class="collapsible" onclick="collapseClick('classes')">Classes</button>
				</div>
				<div class="segcollapse">
				    <button type="button" class="collapsible" onclick="collapseClick('misc')">Misc Fields</button>
				</div>
				<div class="segcollapse">
				    <button type="button" class="collapsible" onclick="collapseClick('payments')">Payments</button>
				</div>
                    <div class="collapsecontent" id="classes">
                    <?php 
                        //# get the program's available classes
                        $progid = $applicationrow['ProgramID'];
                        $classq = mysqli_query($conn,"Select AvailCourse1,AvailCourse2,AvailCourse3,AvailCourse4,AvailCourse5,AvailCourse6,AvailCourse7,AvailCourse8,AvailCourse9,AvailCourse10,AvailCourse11,AvailCourse12,AvailCourse13,AvailCourse14,AvailCourse15 From Program where ProgramID='$progid'");
                        $classqrow = mysqli_fetch_array($classq);
                        
                        //# store the CourseIDs of AvailCourse1-10 in $classqids
                        $classqids = array();
                        for($i=1;$i<11;$i++) {
                            $ac = "AvailCourse" . $i;
                            if(strlen($classqrow[$ac])>0) {
                                array_push($classqids,$classqrow[$ac]);   
                            }
                        }
                        
                        //# retrieve all information about the available courses from Classes and store in $classqfullrow
                        $classqfullrow = array();
                        foreach($classqids as $id) {
                            $tempq = mysqli_query($conn,"Select * From Classes Where CourseID='$id'");
                            $tempr = mysqli_fetch_array($tempq);
                            array_push($classqfullrow,$tempr['CourseID'],$tempr['Course'],$tempr['CourseName'],$tempr['CreditHours']);
                        }
                        $countfull = count($classqfullrow);
                        
                        //# store the applicant's current class ids in $classarr
                        $classarr = array();
                        for($i=1;$i<11;$i++) {
                            $temp = "Course" . $i;
                            array_push($classarr,$applicationrow[$temp]);
                        }
                        echo "<br />";
                        //# echo 10 Selects for the class slots - current value and the classes available
                        for($i=0;$i<10;$i++) {
                            $tempid = $classarr[$i];
                            $tempq = mysqli_query($conn,"Select * From Classes where CourseID='$tempid'");
                            $tempr = mysqli_fetch_array($tempq);
                            $count = $i + 1;
							?>
                            Class <?php echo $count ?>: 
                                <select name="classes<?php echo$count ?>">
                                    <?php 
                                    if(strlen($tempr['CourseID']) > 0) { ?>
                                    <option value="<?php echo $tempr['CourseID'] ?>"><?php echo $tempr['Course'] . " - " . $tempr['CourseName'] . " (" . $tempr['CreditHours'] . ")" ?>
                                    <?php } 
                                    else { ?>
                                        <option value="">
                                    <?php } ?>
							<?php
                            for($j=0;$j<$countfull;$j+=4) {
                                if(($classqfullrow[$j] !== $tempid) && (strlen($classqfullrow[$j]) > 0)) {  ?>
                                    <option value="<?php echo $classqfullrow[$j] ?>"><?php echo $classqfullrow[$j+1] . " - " . $classqfullrow[$j+2] . " (" . $classqfullrow[$j+3] . ")" ?>
                                <?php }
                            } ?>
								</select>
							<br />
                        <?php } ?>
                    </div>
                    <div class="collapsecontent" id="misc">
                     <?php
                        $check = 0;
    				    for($i=1;$i<11;$i++) {
    				        if(strlen($programrow['MiscField$i'])>0) {
    				            $mf = $programrow['MiscField$i'];
    				            $check++;
    				            echo "Misc Field ".$i.": <input type=\"text\" value=\"".$mf."\"><br/>";
    				        }
    				    }
    				    if($check==0){
    				        echo "No Miscellaneous Fields for this Program";
    				    }
    				?>
                    </div>
                    <div class="collapsecontent" id="payments">
                    <?php
                        //# store program payment information
                        $selopts = array("Yes","No");
                    ?>
                        <div>
                            Balance Due: <input type="text" id="bal" name="bal" value="<?php echo $applicationrow['BalanceDue'] ?>">
                            <input type="hidden" id="fulldue" value="<?php echo $programrow['BalanceDue'] ?>">
                        </div>
                        <div>
                            Deposit Paid: <select id="deppaid" name="deppaid">
                                <option value="<? echo $applicationrow['DepositPaid'] ?>"><?php echo $applicationrow['DepositPaid'] ?>
                            <?php
                                foreach($selopts as $c) {
                                    if($c != $applicationrow['DepositPaid']) { ?>
                                        <option value="<?php echo $c ?>"><?php echo $c ?>
                                    <?php }
                                } ?>
							</select>
							Deposit Date and Time Paid: <input type="text" class="datetime" name="depositdt" value="<?php echo $applicationrow['DepositDatePaid']?>">
							Deposit Amount: <input type="text" class="short" id="depamt" disabled value="<?php echo $programrow['DepositAmount'] ?>">
							Deposit Date Due: <input type="text" class="datelength" disabled value="<?php echo $programrow['DepositDateDue'] ?>">
                        </div>
                        <?php 
                            for($i=1;$i<5;$i++) {
                                $pamt = "Payment" . $i . "Amt";
                                $pdp = "Payment" . $i . "DatePaid";
                                $pdd = "Payment" . $i . "DateDue";
                                $pconf = "Payment" . $i . "Paid";
                                
                                if($programrow[$pamt] != 0){ ?>
                                    <div> 
										Payment <?php echo $i ?> Paid: <select id="p<?php echo $i ?>paid" name="p<?php echo $i?>paid">
                                        <option value="<?php echo $applicationrow[$pconf] ?>"><?php echo $applicationrow[$pconf] ?>
                                        <?php 
										foreach($selopts as $c) {
                                            if($c!=$applicationrow[$pconf]) { ?>
                                                <option value="<?php echo $c ?>"><?php echo $c ?>
                                            <?php }
                                        } ?>
                                        </select>
                                        Payment <?php echo $i ?> Paid (Date and Time): <input type="text" class="datetime" name="p<?php echo $i ?>dt" value="<?php echo $applicationrow[$pdp]?>">
                                        Payment <?php echo $i ?> Amount: <input type="text" id="p<?php echo $i ?>amt" class="short" disabled value="<?php echo $programrow[$pamt] ?>">
                                        Payment <?php echo $i ?> Date Due: <input type="text" class="datelength" disabled value="<?php echo $programrow[$pdd] ?>">
                                        </div>
                                <?php }
                            } ?>
                        <div>
                            Insurance Paid: <select id="insurpaid" name="insurpaid">
                                <option value="<? echo $applicationrow['InsurancePaid'] ?>"> <?php echo $applicationrow['InsurancePaid'] ?>
                            <?php
                                foreach($selopts as $c) {
                                    if($c != $applicationrow['InsurancePaid']) { ?>
                                        <option value="<?php echo $c ?>"><?php echo $c ?>
                                    <?php }
                                } ?>
							</select>
							Insurance Date and Time Paid: <input type="text" class="datetime" name="insurdt" value="<?php echo $applicationrow['InsuranceDatePaid']?>">
							Insurance Amount: <input type="text" class="short" id="insamt" disabled value="<?php echo $programrow['InsuranceAmt'] ?>">
							Insurance Date Due: <input type="text" class="datelength" disabled value="<?php echo $programrow['InsuranceDateDue'] ?>">
                        </div>
                    </div>
			</fieldset>
		</div>
    <button class="save" type="submit" name="update" value="update">Save Changes</button>
</form>
</body>
<script>
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace("active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
<script>
function collapseClick(target) {
    var coll = document.getElementsByClassName("collapsecontent");
    for(var i = 0; i < coll.length; i++) {
        coll[i].classList.remove("active");
        coll[i].style.display="none";
    }
    document.getElementById(target).classList.add("active");
    document.getElementById(target).style.display = "block";
}
</script>
</html>
