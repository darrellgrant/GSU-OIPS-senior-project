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
  display: block;
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
}

.active, .collapsible:hover {
  background-color: #555;
  color: #eee;
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
  $("#deppaid,#p1paid").change(function(){
    var bal = parseInt($("#totalbal").val(),10);
    var paid = 0;
    alert(paid);
    if($("#deppaid").val() == "Yes") {
        paid += parseInt($("#depamt").val(),10);
    }
    if($("#p1paid").val() == "Yes") {
        paid += parseInt($("#p1amt").val(),10);
    }
    if($("#p2paid").val() == "Yes") {
        paid += parseInt($("#p2amt").val(),10);
    }
    if($("#p3paid").val() == "Yes") {
        paid += parseInt($("#p3amt").val(),10);
    }
    if($("#p4paid").val() == "Yes") {
        paid += parseInt($("#p4amt").val(),10);
    }
    if($("#insurpaid").val() == "Yes") {
        paid += parseInt($("#insamt").val(),10);
    }
    var a = bal - paid;
    $("#bal").val(a);
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
    
    <button class="close" onclick="parent.closeEdit()">Cancel</button>
    <h2>Editing <?php echo $eagleid . " - " . $personalrow['FirstName'] . " " . $personalrow['LastName'] ?></h2>
    <div class="tab">
      <button class="tablinks" onclick="openTab(event, 'Personal')">Personal</button>
      <button class="tablinks" onclick="openTab(event, 'Emergency')">Emergency Contact</button>
      <button class="tablinks" onclick="openTab(event, 'Passport')">Passport</button>
      <button class="tablinks" onclick="openTab(event, 'Education')">Education</button>
      <button class="tablinks" onclick="openTab(event, 'Application')">Application</button>
    </div>
    <form id="savechange" action="updatestudent.php" method="POST">
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
							<option value="<?php echo $passportrow['PassportVerif'] . "\">" . $passportrow['PassportVerif']; 
							    $ppvarr = array("Yes","No");
							    foreach($ppvarr as $c) {
							        if($c != $passportrow['PassportVerif']) {
							            echo "<option value=\"" . $c . "\">" . $c;
							        } 
							    }
								?>
					    </select>
					</div>
				</div>
				<div class="row">
    				<div class="column2">First name on passport: </div>
    				<div class="column"><input type="text" name="passfn" value="<?php echo $passportrow['PassFName']?>" pattern="[a-zA-Z0-9\s]+"></div>
    				<div class="column2med">Middle name on passport: </div>
    				<div class="column"></column><input type="text" name="passmn" value="<?php echo $passportrow['PassMName']?>" pattern="[a-zA-Z0-9\s]+"></div>
				</div>
				<div class="row">
				    <div class="column2med">Last name on passport: </div>
    				<div class="column"><input type="text" name="passln" value="<?php echo $passportrow['PassLName']?>" pattern="[a-zA-Z0-9\s]+"></div>
				    <div class="column2">Passport Nationality: </div>
				    <div class="columnlong"><input class="long" list="countries" id="passnatl" name="passnatl" pattern="[^\x22|\x3B]+" value="<?php echo $passportrow['PassportNatl']?>">
				        <datalist id="countries">
				            <?php 
				                foreach($countries as $c) {
				                    echo "<option value=\"".$c."\">";
				                }
				            ?>
				        </datalist>
				    </div>
				</div>
				<div class="row">
				    <div class="column2">Passport Number: </div>
				    <div class="column"><input type="text" name="passnum" value="<?php echo     $passportrow['PassportNum']?>" pattern="[^\x22|\x3B]+"></div>
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
    							<option value="<?php echo $educationrow['Class'] . "\">" . $educationrow['Class'];
    								$classarr = array("Freshman","Sophomore","Junior","Senior","Graduate","Other");
    								foreach($classarr as $c) {
    									if($c != $educationrow['Class']) {
    										echo "<option value=\"" . $c . "\">" . $c;
    								    }
    							    }
    						    ?>
    					</select></div>
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
    						<option value="<?php echo $educationrow['AcademicStanding'] . "\">" . $educationrow['AcademicStanding'];
    						    $easarr = array("Yes","No");
    						    foreach($easarr as $c) {
    						        if($c != $educationrow['AcademicStanding']) {
    						            echo "<option value=\"" . $c . "\">" . $c;
    						        }
    						    }
    						?>
						</select></div>
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
    						<option value="<?php echo $applicationrow['GPAVerif'] . "\">" . $applicationrow['GPAVerif'];
    						$arr = array("Yes","No");
    						foreach($arr as $c) {
    						    if($c != $applicationrow['GPAVerif']) {
    						    	echo "<option value=\"" . $c . "\">" . $c;
    					    	}
    						}
    						?>
    					</select></div>
				</div>
				<div class="row">
    				<div class="column2">Judicial Verification: </div>
    				<div class="column">
    				    <select id="judverif" name="judverif">
    						<option value="<?php echo $applicationrow['JudicialVerif'] . "\">" . $applicationrow['JudicialVerif'];
    							$arr = array("Yes","No","Open");
    							foreach($arr as $c) {
    							    if($c != $applicationrow['JudicialVerif']) {
    									echo "<option value=\"" . $c . "\">" . $c;
    								}
    							}
    						?>
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
    						<option value="<?php echo $applicationrow['LetterSent'] . "\">" . $applicationrow['LetterSent'];
    							$arr = array("Yes","No");
    							foreach($arr as $c) {
    								if($c != $applicationrow['LetterSent']) {
    									echo "<option value=\"" . $c . "\">" . $c;
    								}
    							}
    							?>
    					</select>
    				</div>
    				<div class="column2med">Enrollment Verification: </div>
    				<div class="column">
    				    <select id="enrollverif" name="enrollverif">
    						<option value="<?php echo $applicationrow['EnrollmentVerif'] . "\">" . $applicationrow['EnrollmentVerif'];
    							$arr = array("Yes","No");
    							foreach($arr as $c) {
    					    		if($c != $applicationrow['EnrollmentVerif']) {
    									echo "<option value=\"" . $c . "\">" . $c;
    									}
    								}
    							?>
    					</select>
    				</div>
				</div>
				<br>
				<div class="segcollapse">
    				<button type="button" class="collapsible">Classes</button>
                    <div class="collapsecontent">
                    <?php 
                        //# get the program's available classes
                        $progid = $applicationrow['ProgramID'];
                        $classq = mysqli_query($conn,"Select AvailCourse1,AvailCourse2,AvailCourse3,AvailCourse4,AvailCourse5,AvailCourse6,AvailCourse7,AvailCourse8,AvailCourse9,AvailCourse10 From Program where ProgramID='$progid'");
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
                            $count = $i + 1 ;
                            echo "Class " . $count . ": 
                                <select name=\"classes".$count."\">
                                    <option value=\"" . $tempr['CourseID'] . "\">" . $tempr['Course'] . " - " . $tempr['CourseName'] . " (" . $tempr['CreditHours'] . ")";
                            for($j=0;$j<$countfull;$j+=4) {
                                if($classqfullrow[$j] !== $tempid) {                               
                                    echo "<option value=\"" . $classqfullrow[$j] . "\">" . $classqfullrow[$j+1] . " - " . $classqfullrow[$j+2] . " (" . $classqfullrow[$j+3] . ")";
                                    
                                }
                            }
                            echo "</select><br />";
                        }
                        
                    ?>
                    </div>
                </div>
                <div class="segcollapse">
                    <button type="button" class="collapsible">Misc Fields</button>
                    <div class="collapsecontent">
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
                </div>
                <div class="segcollapse">
                    <button type="button" class="collapsible">Payments</button>
                    <div class="collapsecontent">
                    <?php
                        //# store program payment information
                        $selopts = array("Yes","No");
                    ?>
                        <div>
                            Balance Due: <input type="text" id="bal" name="bal" value="<?php echo $applicationrow['BalanceDue'] ?>">
                            <input type="hidden" id="totalbal" value="<?php echo $programrow['BalanceDue'] ?>">
                        </div>
                        <div>
                            Deposit Paid: <select id="deppaid" name="deppaid">
                                <option value="<? echo $applicationrow['DepositPaid'] ?>"> <?php echo $applicationrow['DepositPaid'] ?>
                            <?php
                                foreach($selopts as $c) {
                                    if($c != $applicationrow['DepositPaid']) {
                                        echo "<option value=\"".$c."\">".$c;
                                    }
                                }
                            ?>
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
                                
                                if($programrow[$pamt] != 0){
                                    echo "<div> 
                                    Payment " . $i . " Paid: <select id=\"p".$i."paid\" name=\"p".$i."paid\">
                                        <option value=\"".$applicationrow[$pconf]."\">".$applicationrow[$pconf];
                                        foreach($selopts as $c) {
                                            if($c!=$applicationrow[$pconf]) {
                                                echo "<option value=\"".$c."\">".$c;
                                            }
                                        }
                                        echo "</select>
                                        Payment $i Paid Date and Time: <input type=\"text\" class=\"datetime\" name=\"p".$i."dt\" value=\"".$applicationrow[$pdp]."\">
                                        Payment $i Amount: <input type=\"text\" id=\"p".$i."amt\" class=\"short\" disabled value=\"".$programrow[$pamt]."\">
                                        Payment $i Date Due: <input type=\"text\" class=\"datelength\" disabled value=\"".$programrow[$pdd]."\">
                                        </div>";
                                        
                                }
                            }
                        ?>
                        <div>
                            Insurance Paid: <select id="insurpaid" name="insurpaid">
                                <option value="<? echo $applicationrow['InsurancePaid'] ?>"> <?php echo $applicationrow['InsurancePaid'] ?>
                            <?php
                                foreach($selopts as $c) {
                                    if($c != $applicationrow['InsurancePaid']) {
                                        echo "<option value=\"".$c."\">".$c;
                                    }
                                }
                            ?>
                        </select>
                        Insurance Date and Time Paid: <input type="text" class="datetime" name="insurdt" value="<?php echo $applicationrow['InsuranceDatePaid']?>">
                        Insurance Amount: <input type="text" class="short" id="insamt" disabled value="<?php echo $programrow['InsuranceAmt'] ?>">
                        Insurance Date Due: <input type="text" class="datelength" disabled value="<?php echo $programrow['InsuranceDateDue'] ?>">
                        </div>
                    </div>
                </div>
                <div id="coldisplay">
                </div>
			</fieldset>
		</div>
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
var coll = document.getElementsByClassName("collapsible");
var i;
for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
  var j;
  for (j = 0; j < coll.length; j++) {
    coll[j].className = "collapsible";
  }
  this.classList.toggle("active");

  var content = this.nextElementSibling;
  document.getElementById('coldisplay').innerHTML = content.innerHTML;
  });
}
</script>
</html>
